function Cache()
{
    this.element = new Array();

    this.newElement = function(type)
    {
        return this.doElement('html.' + type, function(){
            return new Element(type);
        });
    };

    this.doElement = function(key, constructor)
    {
        if (!this.issetElement(key)){
            this.setElement(key, constructor());
        }
        return this.getElement(key);
    };

    this.setElement = function(key, element)
    {
        this.element[key] = element;
    };

    this.getElement = function(key)
    {
        if (!this.issetElement(key)){
            return false;
        }
        return this.element[key].cloneNode(true);
    };

    // return true if data is usable
    this.issetElement = function(key)
    {
        if (this.element[key] && typeof this.element[key] != 'undefined'){
            return true;
        }
        return false;
    };

    // remove en entry
    this.removeElement = function(key)
    {
        this.element[key] = null;
    };

    // remove all data
    this.flushElement = function()
    {
        this.element = new Array();
    };
}
