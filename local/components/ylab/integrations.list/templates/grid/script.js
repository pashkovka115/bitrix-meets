BX.namespace("BX.Ylab.Integrations.Grid.LeftPanel");

BX.Ylab.Integrations.Grid.LeftPanel.action = function (url, data) {
    var form = document.createElement('form');
    document.body.appendChild(form);
    form.target = '_self';
    form.method = 'post';
    form.action = url;
    for (var name in data) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = data[name];
        form.appendChild(input);
    }
    form.submit();
    document.body.removeChild(form);

}
BX.Ylab.Integrations.Grid.LeftPanel.create = function (url, data) {
    let _self = new BX.Ylab.Integrations.Grid.LeftPanel.action(url, data)
    return _self
}
///////////


var popup = function () {

    return new BX.CDialog({
        'title': 'Добавление интеграций',
        'content_url': '/local/components/ylab/integrations.list/templates/grid/popups/addintegrationform.php',
        'draggable': true,
        'resizable': true,
        'buttons': [{
            'title': 'Добавить интеграцию',
            'id': 'add-button',
            'action': submit_button
        }
        ]
    })

}

function submit_button() {
    //document.getElementById('errors').innerHTML = '';
    const integration_name = BX('input-name')
    const integration_activity = BX('input-activity')
    const integration_ref = BX('input-inegrationref')
    const integration_login = BX('input-login')
    const integration_password = BX('input-password')

    new function () {
        document.getElementById('errors').innerHTML = ''
        var request = BX.ajax.runAction('ylab:meetings.api.integrationcontroller.add', {
            data: {
                fields: {
                    'NAME': integration_name.value,
                    'ACTIVITY': integration_activity.value,
                    'INTEGRATION_REF': integration_ref.value,
                    'LOGIN': integration_login.value,
                    'PASSWORD': integration_password.value
                }
            }
        });
        request.then(function (response) {
            if (!response['data']['IS_SUCCESS']) {
                var errors = response['data']['ERROR_MESSAGES']
                for (var i in errors) {
                    document.getElementById('errors').innerHTML +=
                        '<p><span style=\"color: red; \">' + errors[i] + '</span> </p>'
                }
            }
        });
    }
}


