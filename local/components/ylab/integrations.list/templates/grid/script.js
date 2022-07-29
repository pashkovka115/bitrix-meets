BX.namespace("BX.Ylab.Integrations.LeftPanel")


BX.Ylab.Integrations.LeftPanel.action = function (data) {
    if (data['action'] === 'delete_burger') {
        var request = BX.ajax.runAction('ylab:meetings.api.integrationcontroller.delete', {
            data: {
                'id': data['id']
            }
        })
        var grid = BX.Main.gridManager.getInstanceById('ylab_meetings_integrations_list')
        BX.UI.Dialogs.MessageBox.alert("Интеграция с ID: " + data['id'] + " удалена!",
            "Сообщение о добавлении интеграции");
        grid.reloadTable()
    }
}


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
        })
        request.then(function (response) {
            if (!response['data']['IS_SUCCESS']) {
                var errors = response['data']['ERROR_MESSAGES']
                for (var i in errors) {
                    document.getElementById('errors').innerHTML +=
                        '<p><span style=\"color: red; \">' + errors[i] + '</span> </p>'
                }
            }
            if (response['data']['IS_SUCCESS']) {

                BX.WindowManager.Get().Close()
                var grid = BX.Main.gridManager.getInstanceById('ylab_meetings_integrations_list')
                BX.UI.Dialogs.MessageBox.alert("Интеграция '" + integration_name.value + "' успешно добавлена!",
                    "Сообщение о добавлении интеграции");
                grid.reloadTable();
            }
        })
    }
}