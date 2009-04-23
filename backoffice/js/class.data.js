function Data()
{
    this.n = 0;
    this.total = 0;
    this.base = new Array();

    // return the number of elements we can use
    this.getTotal = function()
    {
        return this.total;
    }

    // move pointer to the position
    this.seek = function(n)
    {
        if (n > this.base.length - 1){
            return false;
        }
        this.n = n;
        return true;
    }

    // return true if data is usable
    this.isOK = function(field, n)
    {
        if (!n){
            var n = this.n;
        }
        if (this.base[this.n][field] && !this.base[this.n][field].blank()){
            return true;
        }
        return false;
    }

    // return the field value at the curent or specific position
    this.get = function(field, n)
    {
        if (!n){
            var n = this.n;
        }
        if (!this.base[n][field]){
            return '';
        }
        return this.base[n][field];
    }

    // set a field's value at the curent or specific position
    this.set = function(field, value, n)
    {
        if (!n){
            var n = this.n;
        }
        return this.base[this.n][field] = value;
    }

    // grab all params at the curent or specific position
    this.grab = function(n)
    {
        if (!n){
            var n = this.n;
        }
        return this.base[n];
    }

    // replace all params at the curent or specific position
    this.replace = function(param, n)
    {
        if (!n){
            var n = this.n;
        }
        this.base[n] = param;
    }

    // insert a new entry
    this.insert = function(param)
    {
        this.base.push(param);
        this.total ++;
    }

    // remove en entry
    this.remove = function(n)
    {
        if (!n){
            var n = this.n;
        }
        for (var i = n; i < this.total - 1; i ++){
            this.base[i] = this.base[i + 1];
        }
        this.total --;
    }

    // replace curent data
    this.load = function(JSON)
    {
        this.base = JSON;
        this.total = this.base.length;
    }

    // remove all data
    this.flush = function()
    {
        this.n = 0;
        this.total = 0;
        this.base = new Array();
    }
}
