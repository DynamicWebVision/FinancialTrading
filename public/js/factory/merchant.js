app.factory('Merchant', function($http, $q) {

    var service = {};


    //Return Basic Data on Load
    service.index = function(url) {
        var index = $http.get('/merchant/'+url);

        index.then(function(data){
            return data;
        }, function errorCallback(response) {
            window.location='/';
        });
        return index;
    }

    //Create a New Merchant Attribute
    service.create = function(url, post_data) {
        var post = $http.post('/merchant/'+url, post_data);

        post.then(function(data){
            return data;
        });
        return post;
    }

    //Delete a Merchant Attribute
    service.delete = function(url, id) {
        var destroy = $http.delete('/merchant/'+url+'/'+id);

        destroy.then(function(data){
            return data;
        });
        return destroy;
    }

    //Update a Merchant
    service.update = function(url, put_data) {
        var put = $http.put('/merchant/'+url, put_data);

        put.then(function(data){
            return data;
        });
        return put;
    }

    //Update a Merchant
    service.updateMerchant = function(post_data, url) {
        var update = $http.post('/merchant/update/'+url, post_data);

        update.then(function(data){
            return data;
        });
        return update;
    }

    //Merchant Delete
    service.deleteMerchant = function(post_data, url) {
        var destroy = $http.post('/merchant/delete/'+url, post_data);

        destroy.then(function(data){
            return data;
        });
        return destroy;
    }

    //Merchant Create
    service.createMerchant = function(post_data, url) {
        var create = $http.post('/merchant/create/'+url, post_data);

        create.then(function(data){
            return data;
        });
        return create;
    }

    service.getGeneralInfo = function() {
        var get_general_info = $http.get('/merchant/general_info');

        get_general_info.then(function(data){
            return data;
        });
        return get_general_info;
    }

    service.getSendOrder = function() {
        var get_send_order = $http.get('/merchant/send_order');

        get_send_order.then(function(data){
            return data;
        });
        return get_send_order;
    }

    //Updates a Merchant's Location
    service.updateLocation = function(location_data) {
        var update_location = $http.post('/merchant/update/location', location_data);

        update_location.then(function(data){
            return data;
        });
        return update_location;
    }

    //Updates a Merchant's Configurations
    service.updateConfig = function(config_data) {
        var update_config = $http.post('/merchant/update/config', config_data);

        update_config.then(function(data){
            return data;
        });
        return update_config;
    }

    service.editMerchantContact = function(contact_data) {
        var edit_merchant_contact = $http.post('/merchant/update/contact', contact_data);

        edit_merchant_contact.then(function(data){
            return data;
        });
        return edit_merchant_contact;
    }

    service.getContactInfo = function() {
        var get_contact_info = $http.get('/merchant/contact');

        get_contact_info.then(function(data){
            return data;
        });
        return get_contact_info;
    }


    service.getSalesTax = function() {
        var get_sales_tax = $http.get('/merchant/sales_tax');

        get_sales_tax.then(function(data){
            return data;
        });
        return get_sales_tax;
    }


    service.getHours = function() {
        var get_hours = $http.get('/merchant/hours');

        get_hours.then(function(data){
            return data;
        });
        return get_hours;
    }

    service.deleteAdmnEmail = function(id) {
        var delete_admin_email = $http.post('/merchant/delete_admn_email', {admin_email_id: id});

        delete_admin_email.then(function(data){
            return data;
        });
        return delete_admin_email;
    }

    service.saveAdmnEmails = function(admn_emails) {
        var edit_admn_emails = $http.post('/merchant/update_admn_emails', {admn_emails: admn_emails});

        edit_admn_emails.then(function(data){
            return data;
        });
        return edit_admn_emails;
    }

    service.saveAdmnPhone = function(admn_phone) {
        var edit_admn_phone = $http.post('/merchant/update_admn_phone', admn_phone);

        edit_admn_phone.then(function(data){
            return data;
        });
        return edit_admn_phone;
    }

    service.getOrdering = function() {
        var get_ordering = $http.get('/merchant/ordering');

        get_ordering.then(function(data){
            return data;
        });
        return get_ordering;
    }

    service.getDelivery = function() {
        var get_delivery = $http.get('/merchant/delivery');

        get_delivery.then(function(data){
            return data;
        });
        return get_delivery;
    }

    service.updateLeadTime = function(lead_time) {
        var edit_lead_time = $http.post('/merchant/update/lead_time', lead_time);

        edit_lead_time.then(function(data){
            return data;
        });
        return edit_lead_time;
    }

    service.updateDeliveryInfo = function(delivery_info) {
        var edit_delivery = $http.post('/merchant/update/delivery', delivery_info);

        edit_delivery.then(function(data){
            return data;
        });
        return edit_delivery;
    }

    service.updateFixedTax = function(fixed_tax) {
        var edit_fixed_tax = $http.post('/merchant/update/fixed_tax', fixed_tax);

        edit_fixed_tax.then(function(data){
            return data;
        });
        return edit_fixed_tax;
    }

    service.updateTax = function(tax) {
        var edit_tax = $http.post('/merchant/update/tax', tax);

        edit_tax.then(function(data){
            return data;
        });
        return edit_tax;
    }


    return service;
});