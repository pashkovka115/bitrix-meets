(function () {
        BX.namespace("BX.Ylab.Integrations")

        BX.Ylab.Integrations = {
            LeftPanelAction: function LeftPanelAction(data) {
                if (data['action'] === 'delete_burger') {
                    var request = BX.ajax.runAction('ylab:meetings.api.integrationcontroller.delete', {
                        data: {
                            'id': data['id']
                        }
                    })
                    request.then(function (response) {
                        if (response['data']['IS_SUCCESS']) {
                            var grid = BX.Main.gridManager.getInstanceById('ylab_meetings_integrations_list')
                            BX.UI.Dialogs.MessageBox.alert("Интеграция с ID: " + data['id'] + " удалена!",
                                "Сообщение о добавлении интеграции");
                            grid.reloadTable()
                        }
                    })
                }
                if (data['action'] === 'edit_burger') {

                    var popup = this.PopUp('edit')

                    var request = BX.ajax.runAction('ylab:meetings.api.integrationcontroller.getFields', {
                        data: {
                            'id': data['id']
                        }
                    })
                    request.then(function (response) {
                        if (response['data']['IS_SUCCESS']) {
                            popup.Show()
                            var timer = setInterval(function () {
                                var addForm = document.querySelector("#add-form");
                                if (addForm) {
                                    clearInterval(timer);

                                    document.getElementById('input-id').value = response['data']['FIELDS']['ID']
                                    document.getElementById('input-name').value = response['data']['FIELDS']['NAME']
                                    document.getElementById('input-activity').value = response['data']['FIELDS']['ACTIVITY']
                                    document.getElementById('input-inegrationref').value = response['data']['FIELDS']['INTEGRATION_REF']
                                    document.getElementById('input-login').value = response['data']['FIELDS']['LOGIN']
                                    document.getElementById('input-password').value = response['data']['FIELDS']['PASSWORD']
                                }
                            }, 100);
                        }
                    })
                }
            },
            PopUp: function PopUp(action) {
                return new BX.CDialog({
                    'title': 'Добавление интеграций',
                    'content_url': '/local/components/ylab/integrations.list/templates/grid/popups/addintegrationform.php',
                    'draggable': true,
                    'resizable': true,
                    'buttons': [{
                        'title': 'Подтвердить',
                        'id': 'add-button',
                        'action': action === 'add' ? this.GetSubmitAddButton : action === 'edit' ? this.GetSubmitEditButton : null
                    }
                    ]
                })
            }

            ,

            GetSubmitAddButton: function GetSubmitAddButton() {
                const integration_name = BX('input-name')
                const integration_activity = BX('input-activity')
                const integration_ref = BX('input-inegrationref')
                const integration_login = BX('input-login')
                const integration_password = BX('input-password')

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
                            "Сообщение о добавлении интеграции")
                        grid.reloadTable();
                    }
                })
            }
            ,
            GetSubmitEditButton: function GetSubmitEditButton() {
                const integration_id = BX('input-id')
                const integration_name = BX('input-name')
                const integration_activity = BX('input-activity')
                const integration_ref = BX('input-inegrationref')
                const integration_login = BX('input-login')
                const integration_password = BX('input-password')

                document.getElementById('errors').innerHTML = ''

                var request = BX.ajax.runAction('ylab:meetings.api.integrationcontroller.update', {
                    data: {
                        id: integration_id.value,
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
                        BX.UI.Dialogs.MessageBox.alert("Интеграция с ID: " + integration_id.value + " изменена!",
                            "Сообщение о добавлении интеграции")
                        grid.reloadTable();
                    }
                })
            }
            ,
        }
    }
)
()