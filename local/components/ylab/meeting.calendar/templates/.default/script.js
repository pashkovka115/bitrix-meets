BX.namespace("Ylab.MeetingCalendar");

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
            console.log(buttonsCont);
        }
    }

    BX.Ylab.MeetingCalendar.create = function()
    {
        let _self = new BX.Ylab.MeetingCalendar();
        return _self;
    };
});

BX.ready(function() {
    let elementSearch = document.querySelector(".main-ui-filter-search");
    let elementSelect = BX.create({
        tag: 'div',
        props: {
            className: "main-ui-filter-search"
        },
        children: [
            BX.create({
                tag: 'select',
                props: {
                    className: "main-ui-filter-search-filter"
                },
                children: [
                    BX.create({
                        tag: 'option',
                        props: {
                            value: "select",
                            text: "select"
                        }
                    }),],
            })
        ]
    })

    BX.insertAfter(elementSelect, elementSearch);
});
