Vue.config.delimiters = ['${', '}'];
Vue.config.debug = true;

$(window).load(function() {
    tools.hideLoaderMask();
});

var tools = {
    hideLoaderMask: function() {
        $("#loader").fadeOut("fast");
        $(".mask").fadeOut("fast");
    }
};

var vueMethods = {
    submitForm: function (data, successPath) {
        var form = $('form');
        var formMethod = form.attr('method').toLowerCase();
        Vue.http[formMethod](form.attr('action'), data).then(function (response) {
            // success
            window.location.href = successPath;
        }, function (response) {
            // error
            console.log('error');
        });
    }
};

var regex = {
    email: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i
};
