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