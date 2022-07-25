/*
BX.namespace(BX.YlabMeetingCalendar)
BX.ready(function(){
    BX.YlabMeetingCalendar = function(id) {
        this._id = id;
    }
    BX.YlabMeetingCalendar.create = function(id)
    {
        let _self = new BX.YlabMeetingCalendar(id);
        return _self;
    };
});
*/
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
