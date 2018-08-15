angular.module('app')
    .directive('prettyJson', [function () {
        return function (obj, pretty) {
            if (!obj) {
                return '';
            }
            var json = angular.toJson(obj, pretty);
            if (!pretty) {
                return json;
            }
            return json
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                    var cls;
                    if (/^"/.test(match)) {
                        cls = /:$/.test(match) ? 'key' : 'string';
                    } else if (/true|false/.test(match)) {
                        cls = 'boolean';
                    } else if (/null/.test(match)) {
                        cls = 'null';
                    } else {
                        cls = 'number';
                    }
                    return '<span class="json-' + cls + '">' + match + '</span>';
                });
        };
    }]);/**
 * Created by boneill on 8/15/18.
 */
