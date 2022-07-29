BX.namespace("Ylab.MeetingCalendar");

var arrayMeetingList = '';
window.arElements = function (arParams) {
    if (typeof arParams === 'object')
    {
        arrayMeetingList = arParams;
    }
};
function addOption(){
    var typeSelect = formType.calendarType;
    for (let val of arrayMeetingList){
        var newOption = new Option(val.CALENDAR_TYPE_XML_ID, val.CALENDAR_TYPE_XML_ID);
        typeSelect.options[typeSelect.options.length]=newOption;
    }
}
BX.ready(function(){
    BX.Ylab.MeetingCalendar = function() {
        if (typeof BXEventCalendar != 'undefined') {
            var instances = BX.prop.getObject(BXEventCalendar,  'instances', null);

            if (instances) {
                this._calendar = Object.values(instances)[0];
                this.showButtons();
            }
        }
    }
    BX.Ylab.MeetingCalendar.prototype = {
        showButtons: function (){
            var buttonsCont = BX.prop.get(this._calendar,  'buttonsCont', null);
            let elementSelect = BX.create({
                tag: 'div',
                children: [
                    BX.create({
                        tag: 'form',
                        props: {
                            method: "get",
                            action: "",
                            name: "formType"
                        },
                        children: [
                            BX.create({
                                tag: 'select',
                                attrs: {
                                    className: "main-ui-filter-search-filter",
                                    onchange: "javascript:this.form.submit()",
                                    name: "calendarType"
                                },
                                children: [
                                    BX.create({
                                        tag: 'option',
                                        props: {
                                            value: "",
                                            text: BX.message('SELECT_TYPE')
                                        },
                                    }),
                                ],
                            }),
                        ],
                    }),
                ],
            });
            BX.insertBefore(elementSelect, buttonsCont);
            addOption();
        }
    }

    BX.Ylab.MeetingCalendar.create = function()
    {
        let _self = new BX.Ylab.MeetingCalendar();
        return _self;
    };
});