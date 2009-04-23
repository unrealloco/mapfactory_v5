function TPL()
{
    // get a global param
    this.getParam = function(attribute)
    {
        return template[Menu.loadedType][attribute];
    }
    
    // get a field's value
    this.getFieldValue = function(field, attribute)
    {
        return template[Menu.loadedType]['field'][field][attribute];
    }
    
    // return all fields
    this.getFieldList = function()
    {
        return template[Menu.loadedType].field;
    }
    
    // return all tools
    this.getToolList = function()
    {
        return template[Menu.loadedType].tool;
    }
}
