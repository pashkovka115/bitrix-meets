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