'use strict';

angular
    .module('tasks')
    .controller('TasksController', TasksController);

TasksController.$Inject = ['$scope', '$mdDialog', '$timeout', '$q', '$cookieStore', '$filter', '$state', 'logger', 'Tasks', 'Clients', 'Projects', 'Users', 'Authentication', 'Authorization', 'moment', 'appConfig', 'clientHelper', 's3Storage', 'tasksHelper'];

function TasksController($scope, $mdDialog, $timeout, $q, $cookieStore, $filter, $state, logger, Tasks, Clients, Projects, Users, Authentication, Authorization, moment, appConfig, clientHelper, s3Storage, tasksHelper) {
    var EXCLUDE_DAYS_ISO = [6, 7]; // Sat-Sun
    var EXCERPT_CHAR_LIMIT = 400;
    var LINK_REGEX = /\b(((http|https):\/\/)|www\.)(\S+)\b/gi;

    $scope.me = Authentication.user;
    $scope.readonly = !Authorization.isGranted('ManageTasks', $scope.me);

    $scope.tasks = [];
    $scope.clients = [];
    $scope.projects = [];
    $scope.loading = false;

    $scope.priorities = appConfig.priorities;
    $scope.estimates = appConfig.estimates;
    $scope.statuses = appConfig.taskStatuses;
    $scope.today = moment().startOf('day');

    $scope.ui = {};
    $scope.ui.tasksPastDue = [];
    $scope.ui.tasksScheduled = [];
    $scope.ui.tasksUnscheduled = [];
    $scope.ui.collaborators = [];
    $scope.ui.projects = [];
    $scope.ui.lastRelatedProject = null;
    $scope.ui.showArchivedTasks = false;

    var by = $scope.by = {
        dueDate: function (task) { return moment(task.dueDate || '3000-01-01').startOf('day').valueOf(); },
        scheduledDate: function (task) { return moment(task.scheduledDate || '3000-01-01').startOf('day').valueOf(); },
        status: function (task) { return _.indexOf($scope.statuses, task.status); },
        priority: function(task) { return task.priority ? _.indexOf($scope.priorities, task.priority) : Infinity; },
        estimate: function(task) { return -moment.duration(task.estimate || 0).valueOf(); },
        archived: function(task) { return $scope.isArchived(task); }
    };

    $scope.statusKeys = _.without($scope.statuses, 'Open');

    $scope.resolve = _.findById;
    $scope.clone = _.clone;
    $scope.codeOf = _.codeOf;

    $scope.sortableTasks = {
        disabled: $scope.readonly,
        connectWith: '.tasks-list',
        tolerance: 'pointer',
        start: function (e, ui) {
            $(ui.item).addClass('dragging');
        },
        beforeStop: function(e, ui) {
            $(ui.item).removeClass('dragging');

            if ($scope.readonly) return false;
            if ($('.nodrop').find('.ui-sortable-placeholder').length) {
                return false; // cancel drop to .nodrop container
            }
        },
        stop: function (e, ui) {
            $(ui.placeholder).remove();
        },
        receive: function (event, ui) {
            var taskId = taskCardId(ui.item);
            var task = _.findById($scope.tasks, taskId);
            if (!task) return console.warn('unable to find task ' + taskId);

            // sortable model (tasks collection) is updated synchronously,
            // that's why rescheduling task asynchronously (afterwards)
            $timeout(function () {
                $scope.updateTask(task, rescheduleTaskUpdates(task));
            });
        }
    };

    $scope.addTaskModal = function (ev, project, details) {
        var task = new Tasks();

        if (project) {
            task.relatedProjects = [_.id(project)];
        }
        else {
            var projects = $scope.relatedProjects($scope.selectedClient);
            if (projects.length == 1) {
                task.relatedProjects = _.pluck(projects, '_id');
            }
        }
        angular.extend(task, details);
        patchTaskDates(task);
        return openTaskDetailsModal(ev, task);
    };

    $scope.editTaskModal = openTaskDetailsModal;

    $scope.openComments = function (ev, task) {
        var dialogScope = $scope.$new();
        dialogScope.saving = false;
        dialogScope.task = task;

        dialogScope.commentDto = function (task) {
            return {
                taskId: task._id,
                comment: dialogScope.comment
            };
        };

        dialogScope.save = function (comment) {
            dialogScope.saving = true;
            Tasks.postComment({ taskId: comment.taskId }, comment, function (comment) {
                comment.user = $scope.me._id;
                comment.timestamp = new Date();
                dialogScope.task.comments.push(comment);

                logger.success('Comment posted successfully');
                $mdDialog.hide();
            }, function (response) {
                console.error(response);
                logger.error(response.data.message);
            }).$promise.finally(function () {
                    dialogScope.saving = false;
                });
        };

        dialogScope.close = function () {
            $mdDialog.cancel();
        };

        return $mdDialog.show({
            templateUrl: 'modules/tasks/views/task-comments-dialog.client.view.html',
            scope: dialogScope,
            parent: angular.element(document.body),
            targetEvent: ev,
            focusOnOpen: false
        });
    };

    $scope.updateTask = function (task, properties) {
        if ($scope.readonly) return $q.reject();

        task.saving = true;
        var result = Tasks.update({ taskId: task._id }, properties || task, null, function (err) {
            task.saving = false;
            console.error(err);
            logger.error('Failed to update task ' + task.title);
        }).$promise;

        return result.then(function (updatedTask) {
            $timeout(function () {
                task.saving = false;
            });

            if (properties) {
                angular.extend(task, updatedTask);
                patchTaskDates(task);
            }

            if (task.ui) {
                task.ui.status = null;
                task.ui.editing = null;
            }

            logger.success('Task "' + task.title + '" has been updated');

            if (!$state.is('tasksSchedule')) {
                $scope.refreshTasks();
            }

            return task;
        });
    };

    $scope.remove = function (task, force) {
        if ($scope.readonly) return;

        if (!force) {
            $scope.selectedTask = task;
            return $('#deleteTaskModal').modal2('show');
        }

        task.saving = true;
        Tasks.remove({ taskId: task._id }, function () {
            $('#deleteTaskModal').modal2('hide');
            task.saving = false;
            removeItem($scope.tasks, task);
            logger.info('Task "' + task.title + '" has been removed');
            $scope.refreshTasks();
        }, function (err) {
            console.error(err);
            logger.error('Failed to remove task');
        });
    };

    $scope.resetFocus = function () {
        $(document.activeElement).blur();
        $(document).focus();
    };

    $scope.selectFile = function (ev) {
        if ($scope.readonly) return;
        $(ev.target).closest('md-input-container').find('input[type="file"]').click();
    };

    $scope.uploadFile = function (task, file) {
        if ($scope.readonly) return;

        var collection = task.attachments;
        var clients = _.chain($scope.projects).findById(task.relatedProjects).pluck('client').uniq().value();

        var key = s3Storage.resolveKey(file.name, clients.length == 1 ? clients[0].companyName : null).replace(/(\..+)?$/i, '-attachment$1');
        s3Storage.upload(key, file).then(function (url) {
            delete $scope.uploadProgress;
            collection.push({
                name: file.name,
                key: key,
                url: url,
                added: true
            });
        }, null, function (progress) { $scope.uploadProgress = progress; });
    };

    $scope.removeFile = function (collection, file) {
        if ($scope.readonly) return;
        file.removed = true;
    };

    $scope.filterByClient = function (client) {
        $scope.selectedClient = client;
        $cookieStore.put('selected-client', client ? client.companyName : null);

        var allCollaborators = _.filter($scope.users, function (user) {
            return Authorization.isGranted('ManageTasks', user);
        });

        $scope.ui.collaborators = !$scope.selectedClient ? allCollaborators : _.filter(allCollaborators, function (user) {
            var clients = clientHelper.resolveClients(user, [$scope.selectedClient]);
            return _.contains(clients, $scope.selectedClient);
        });

        var tasks = $scope.tasks;

        // filter out scheduled tasks by collaborators
        var clientIds = $scope.selectedClient ? [$scope.selectedClient._id] : _.pluck($scope.clients, '_id');
        tasks = _.filter(tasks, function (task) {
            var relatedProjects = _.findById($scope.projects, task.relatedProjects);
            return _.some(relatedProjects, function (p) {
                return _.contains(clientIds, _.id(p.client));
            });
        });

        // filter client team members only
        if ($scope.me.role == 'client' || $scope.me.role == 'client-team') {
            $scope.ui.collaborators = _.filter($scope.ui.collaborators, function (user) {
                return user.role == 'client' || user.role == 'client-team';
            });

            tasks = _.filter(tasks, function (task) {
                return !task.assignedTo || _.findById($scope.ui.collaborators, task.assignedTo) != null;
            });
        }

        $scope.ui.tasks = tasks.slice();

        tasks = _.filter(tasks, $scope.isArchived(false));
        $scope.ui.tasksPastDue = _.filter(tasks, $scope.overdueTasks);

        tasks = _.difference(tasks, $scope.ui.tasksPastDue);
        $scope.ui.tasksScheduled = _.filter(tasks, function (task) { return task.scheduledDate && task.assignedTo; });
        $scope.ui.tasksUnscheduled = _.difference(tasks, $scope.ui.tasksScheduled);

        $scope.ui.projects = _.filter($scope.projects, function (project) {
            return _.contains(clientIds, _.id(project.client));
        });

        assignUserTasks($scope.ui.tasksScheduled, $scope.ui.tasksPastDue);

        $scope.filterByProject($scope.selectedProject, true);
    };

    $scope.filterByProject = function (project, ignoreReset) {
        $scope.selectedProject = project;
        $cookieStore.put('selected-project', _.id(project));

        // reset filters
        if (!ignoreReset) $scope.filterByClient($scope.selectedClient);
        if (!$scope.selectedProject) return;

        // apply project filters
        $scope.ui.tasksPastDue = _.filter($scope.ui.tasksPastDue, bySelectedProject);
        $scope.ui.tasksUnscheduled = _.filter($scope.ui.tasksUnscheduled, bySelectedProject);
        $scope.ui.tasksScheduled = _.filter($scope.ui.tasksScheduled, bySelectedProject);
        $scope.ui.tasks = _.filter($scope.ui.tasks, bySelectedProject);
        $scope.ui.projects = [$scope.selectedProject];

        assignUserTasks($scope.ui.tasksScheduled, $scope.ui.tasksPastDue);

        function bySelectedProject(task) {
            return _.contains(task.relatedProjects, _.id($scope.selectedProject));
        }
    };

    $scope.refreshTasks = function () {
        if ($scope.selectedProject) $scope.filterByProject($scope.selectedProject);
        else $scope.filterByClient($scope.selectedClient);
    };

    $scope.relatedProjects = function (client) {
        var projects = _.sortBy($scope.projects, 'title');
        if (client) {
            projects = _.filter(projects, function (project) {
                return _.id(project.client) == _.id(client);
            });
        }
        return projects;
    };

    $scope.relatedTasks = function (project, tasks) {
        var tasks = tasks || $scope.ui.tasks;
        return tasksHelper.findProjectRelated(project, tasks);
    };

    $scope.relatedClientTasks = function (client, tasks) {
        var tasks = tasks || $scope.tasks;
        return tasksHelper.findClientRelated(client, tasks, $scope.projects);
    };

    $scope.commentTimestamp = function (comment) {
        return comment.timestamp && moment(comment.timestamp).unix();
    };

    $scope.lastEdited = function (task) {
        return lastUpdate(task);
    };

    $scope.estimateToComplete = function (project) {
        var tasks = _.filter($scope.relatedTasks(project), function(task) { return task.status == 'Open' || task.status == 'In Progress'; });
        var estimates = _.map(tasks, function (t) { return moment.duration(t.estimate || 0); });
        var total = _.reduce(estimates, function (memo, duration) { return memo.add(duration); }, moment.duration(0));
        return total;
    };

    var tmDuration = $filter('tmDuration');
    $scope.buildEstimates = function (estimates) {
        var res = {};
        _.each(estimates, function (value) {
            res[value] = tmDuration(value);
        });
        return res;
    };

    $scope.prefix = function (prefix, item) {
        return item ? prefix + item : '';
    };

    $scope.visibleFile = function (attachment) {
        return !attachment.removed;
    };

    $scope.truncateExcerpt = function (str) {
        if (typeof str != 'string') return false;
        return str.length > EXCERPT_CHAR_LIMIT;
    };

    $scope.excerpt = function (str) {
        if (!$scope.truncateExcerpt(str)) return str;
        return str.substr(0, EXCERPT_CHAR_LIMIT) + '...';
    };

    $scope.formatText = function (str) {
        var result = (str || '').replace(/\n/g, '<br>').trim();
        result = textifyLinks(result);
        result = urlify(result);
        return result;
    };

    $scope.nextStatus = function (task) {
        switch (task && task.status || task) {
            case 'Open': return 'In Progress';
            case 'In Progress': return 'Ready For Review';
            case 'Ready For Review': return 'Done';
            case 'Done': return 'Archive';
            case 'On Hold': return 'Open';
            case 'Archive': return 'Open';
        }
    };

    $scope.toggleStatus = function (task) {
        if ($scope.readonly) return;

        var next = $scope.nextStatus(task);
        if (next == task.status) return;

        var assignedTo = _.id(task.assignedTo) || $scope.me._id;
        return $scope.updateTask(task, { status: next, assignedTo: assignedTo }).then(function () {
            task.ui.statusHover = false;
        });
    };

    $scope.overdueTasks = function (task) {
        return task.scheduledDate && moment(task.scheduledDate).startOf('day').valueOf() < $scope.today.valueOf();
        // [2:22:38 PM] Bruce van Zyl: don't worry about due date. It's more of an optional item if the client has a demo or meeting before a certain date we'll note it.
        //|| task.dueDate && moment(task.dueDate).startOf('day').valueOf() < (since || $scope.today).valueOf();
    };

    $scope.todayTasks = function (task) {
        return task.scheduledDate && moment(task.scheduledDate).startOf('day').valueOf() == $scope.today.valueOf();
    };

    $scope.futureTasks = function (task) {
        return !task.scheduledDate || moment(task.scheduledDate).startOf('day').valueOf() > $scope.today.valueOf();
    };

    $scope.toggleEstimate = function (task, newEstimate) {
        if (task.estimate == newEstimate) task.estimate = null;
        else task.estimate = newEstimate;
    };

    $scope.whoAssigned = function (task) {
        var lastAssigned = lastUpdate(task, function(details) { return details.changes && details.changes.to.assignedTo; });
        if (!lastAssigned || _.id(lastAssigned.changes.to.assignedTo) != $scope.me._id) return null;
        return lastAssigned.user;
    };

    $scope.assignBack = function (task, user) {
        var request = { assignedTo: user || $scope.whoAssigned(task) };
        $scope.updateTask(task, request).then(function (updatedTask) {
            var user = _.findById($scope.users, updatedTask.assignedTo);
            logger.success('Task `' + task.title + '` has been assigned back to ' + user.displayName);
            $scope.refreshTasks();
        }).catch(function (err) {
            var user = _.findById($scope.users, request.assignedTo);
            logger.error('Failed to assign task `' + task.title + '` back to ' + user.displayName);
        });
    };

    $scope.isArchived = function (task) {
        if (typeof arguments[0] === 'boolean') {
            var value = arguments[0];
            return function(task) { return $scope.isArchived(task) === value; }
        }
        if (!task) return $scope.isArchived(true);

        if (task.status == 'Closed') return true;
        if (task.status == 'Archive') return true;
        if (task.status != 'Done') return false;

        return !task.scheduledDate || moment(task.scheduledDate) < $scope.today;
    };

    $scope.all = function () {
        return true;
    };

    $scope.toggleArchivedTasks = function (ui, ev) {
        ui.showArchivedTasks = !ui.showArchivedTasks;

        if (ev && !ui.showArchivedTasks) {
            $timeout(function() {
                var $target = $(ev.target);
                if ($target.isOutOfView()) {
                    $target.closest('.project').scrollIntoView();
                }
            }, 10);
        }
    };

    $scope.$watchCollection('tasks', $scope.refreshTasks, true);

    $scope.$watch('selectedClient', $scope.filterByClient);
    $scope.$watch('selectedProject', $scope.filterByProject);

    init();

    //
    // PRIVATE FUNCTIONS
    //

    function init() {
        $scope.loading = true;

        var selectedClientName = $cookieStore.get('selected-client') || null;
        var selectedProjectId = $cookieStore.get('selected-project') || null;

        $scope.tasks = Tasks.query();
        $scope.clients = Clients.query();
        $scope.projects = Projects.query();
        $scope.users = Users.getAllUsers();

        $q.all([$scope.tasks.$promise, $scope.clients.$promise, $scope.projects.$promise, $scope.users.$promise]).finally(function () {
            $scope.clients = clientHelper.resolveClients($scope.me, $scope.clients);
            $scope.selectedClient = _.findWhere($scope.clients, { companyName: selectedClientName });
            $scope.selectedProject = _.findWhere($scope.projects, { _id: selectedProjectId });

            if (!$scope.selectedClient && $scope.clients.length == 1) {
                $scope.selectedClient = $scope.clients[0];
            }

            if ($state.is('dashboard')) {
                $scope.selectedClient = null;
                $scope.selectedProject = null;
                $scope.tasks = _.filter($scope.tasks, function (task) { return _.id(task.assignedTo) == $scope.me._id; });
            }

            if ($state.is('tasksSchedule')) {
                $scope.tasks = order($scope.tasks, by.priority, by.status, by.estimate, by.scheduledDate, by.dueDate, 'title');
            }
            else {
                $scope.tasks = order($scope.tasks, by.scheduledDate, by.dueDate, by.status, by.priority, by.estimate, 'title');
            }

            $scope.days = buildSchedule($scope.today);

            // filter out user available models from user context (in scope of assigned clients)
            var clientIds = _.pluck($scope.clients, '_id');
            $scope.projects = _.filter($scope.projects, function (project) { return _.contains(clientIds, _.id(project.client)); });
            $scope.projects = _.sortBy($scope.projects, function (project) { return project.client.companyName + ':' + project.title; });

            $scope.users = _.chain($scope.users).filter(function (user) {
                if (user.client || user.clients) {
                    var clients = clientHelper.resolveClients(user, $scope.clients);
                    if (clients.length == 0) return; // out of scope
                }
                return user;
            }).compact().value();

            _.each($scope.users, function (user) { user.ui = {}; });
            $scope.monthlyReports = loadProjectReports(_.sortBy($scope.projects, 'title'));

            _.each($scope.projects, patchProjectDates);
            _.each($scope.tasks, patchTaskDates);

            var tmpSelectedClient = $scope.selectedClient;
            $scope.filterByClient(null);
            $scope.tasks = $scope.ui.tasks; // filter out user available tasks only
            $scope.filterByClient(tmpSelectedClient);

            $timeout(function () {
                $scope.loading = false;
            });
        });
    }

    function buildSchedule(start) {
        var behindScheduleDays = [];

        //// add previous working day
        //var prev = moment(start);
        //while (!isWorkday(prev.add(-1, 'day')));
        //behindScheduleDays.unshift(prev);

        // add next 14 business days
        var onScheduleDays = [];
        for (var next = moment(start); onScheduleDays.length < 14; next.add(1, 'day')) {
            if (isWorkday(next)) {
                onScheduleDays.push(moment(next));
            }
        }

        var days = behindScheduleDays.concat(onScheduleDays);
        return _.compact(days);
    }

    function assignUserTasks(scheduledTasks, dueTasks) {
        resetUserTasks();

        _.each(scheduledTasks, function (task) {
            var user = _.findById($scope.users, task.assignedTo);
            if (!user) return;

            var date = moment(task.scheduledDate).startOf('day');
            if (!date) return;

            var day = date.format();
            user.ui.tasks[day] = user.ui.tasks[day] || [];
            user.ui.tasks[day].push(task);
        });

        _.each(dueTasks, function (task) {
            var user = _.findById($scope.users, task.assignedTo);
            if (!user) return;
            user.ui.tasksPastDue.push(task);
        });
    }

    function resetUserTasks() {
        _.each($scope.users, function (user) {
            user.ui = user.ui || {};
            user.ui.tasks = {};
            user.ui.tasksPastDue = [];

            _.each($scope.days, function (day) {
                user.ui.tasks[day.format()] = [];
            });
        });
    }

    function isScheduled(task) {
        if (_.contains($scope.ui.tasksScheduled, task)) return true;

        var scheduledTasks = _.chain($scope.ui.collaborators).pluck('ui').pluck('tasks').map(function(schedule) {
            return _.flatten(_.values(schedule));
        }).flatten().value();
        if (_.contains(scheduledTasks, task)) return true;

        return false;
    }

    function rescheduleTaskUpdates(task) {
        var scheduleInfo = {};

        if (isScheduled(task)) {
            var user = _.find($scope.ui.collaborators, function (user) {
                return _.chain(user.ui.tasks).values().flatten().contains(task).value();
            });

            var date = user && _.findKey(user.ui.tasks, function (tasks) { return _.contains(tasks, task); });

            scheduleInfo.assignedTo = _.id(user) || null;
            scheduleInfo.scheduledDate = date && moment(date).toDate() || null;
        }
        else {
            scheduleInfo.scheduledDate = null;
            scheduleInfo.assignedTo = null;
        }

        angular.extend(task, scheduleInfo);

        scheduleInfo._id = task._id;
        scheduleInfo.title = task.title;

        return scheduleInfo;
    }

    function removeItem(arr, item) {
        if (!_.isArray(arr)) return false;
        var idx = _.findIndex(arr, item);
        if (idx < 0) return false;
        arr.splice(idx, 1);
        return true;
    }

    function isWorkday(day) {
        return !_.contains(EXCLUDE_DAYS_ISO, day.isoWeekday());
    }

    function openTaskDetailsModal(ev, task) {
        var dialogScope = $scope.$new();
        dialogScope.originalTask = task;
        dialogScope.task = new Tasks(angular.copy(task));
        dialogScope.editing = !!task._id;

        dialogScope.save = function (task) {
            dialogScope.saving = true;
            task.attachments = cleanUpFiles(task.attachments, { removed: true });
            var saved = dialogScope.editing ? task.$update({ taskId: task._id }) : task.$save();
            return saved.then(function (savedTask) {
                patchTaskDates(savedTask);

                if (!dialogScope.editing) {
                    $scope.tasks.push(savedTask);
                    $scope.ui.tasks.push(savedTask);
                    $scope.ui.tasksUnscheduled.unshift(savedTask);
                    logger.success('New task saved successfully');
                }
                else {
                    angular.extend(dialogScope.originalTask, savedTask);
                    logger.success('Task updated successfully');
                }

                $scope.ui.lastRelatedProject = _.findById($scope.projects, savedTask.relatedProjects[0]);
                $scope.filterByClient($scope.selectedClient);
                $mdDialog.hide();
            }).catch(function (response) {
                console.error(response);
                logger.error(response.data.message);
            }).finally(function () {
                dialogScope.saving = false;
            });
        };

        dialogScope.cancel = function () {
            cleanUpFiles(dialogScope.task.attachments, { added: true });
            $mdDialog.cancel();
        };

        var templateUrl = 'modules/tasks/views/'
            + (dialogScope.editing ? 'task-details-edit-dialog.client.view.html' : 'task-details-create-dialog.client.view.html');

        return $mdDialog.show({
            templateUrl: templateUrl,
            scope: dialogScope,
            parent: angular.element(document.body),
            targetEvent: ev,
            focusOnOpen: false
        });
    }

    function loadProjectReports(projects) {
        var result = {};
        _.each(projects, function (project) {
            result[project._id] = { loading: true };
            return requestSpentTime(project, moment($scope.today).startOf('month')).then(function (duration) {
                result[project._id] = { timeSpent: duration };
            }).catch(function (err) {
                console.error('failed to retrieve Toggl project report', err);
                result[project._id] = { error: true };
            });
        });
        return result;
    }

    function requestSpentTime(project, since) {
        if (!project || !project.togglProjectId) return $q.when(moment.duration(0));
        var query = { projectId: project._id, since: moment(since).format('YYYY-MM-DD') };
        return Projects.detailedReport(query).$promise.then(function (report) {
            var total = moment.duration(report && report.total_grand || 0)
            return total;
        });
    }

    function cleanUpFiles(attachments, criteria) {
        var garbage = _.where(attachments, criteria);
        // todo: clarify and extend S3 policy or remove this line
        //garbage.forEach(function (attachment) { s3Storage.remove(attachment.key); });
        return _.difference(attachments, garbage);
    }

    function order(tasks, order) {
        order = _.params(arguments, 1);
        return $filter('orderBy')(tasks, order);
    }

    function patchProjectDates(project) {
        if (project.dueDateRequested) project.dueDateRequested = moment(project.dueDateRequested).toDate();
        if ((project.schedule || {}).start) project.schedule.start = moment(project.schedule.start).toDate();
        if ((project.schedule || {}).end) project.schedule.end = moment(project.schedule.end).toDate();
        return project;
    }

    function patchTaskDates(task) {
        if (task) {
            if (task.dueDate) task.dueDate = moment(task.dueDate).toDate();
            if (task.scheduledDate) task.scheduledDate = moment(task.scheduledDate).toDate();
        }
        return task;
    }

    function lastUpdate(task, predicate) {
        if (!task) return;
        var last = _.chain(task.updateHistory).filter(predicate)
            .max(function (h) { return moment(h.timestamp).unix(); })
            .orDefault(null).value();
        return last;
    }

    function textifyLinks(html) {
        var $html = $('<div>').html(html);
        $html.find('a').each(function () {
            var $a = $(this);
            $a.replaceWith($a.attr('href') || $a.text());
        });
        return $html.html();
    }

    function urlify(text) {
        var html = text.replace(LINK_REGEX, function(match) {
            var url = match.startsWith('www') ? 'http://' + match : match;
            url = encodeURI(url);
            return '<a href="' + url + '" target="_blank">' + url.replace(/^https?:\/\//g, '') + '</a>';
        });
        return html;
    }

    function taskCardId(taskCard) {
        return $(taskCard).find('> [data-id]').attr('data-id');
    }
}