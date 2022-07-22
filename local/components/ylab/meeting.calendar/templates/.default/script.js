BX.ready(function(){
    BX.TestObject = function(id) {
        this._id = id;
    }
    BX.TestObject.create = function(id)
    {
        let _self = new BX.TestObject(id);
        return _self;
    };
});