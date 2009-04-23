function Pagination()
{
    this.init = function()
    {
        this.shutdown();
        
        $('pagination_next').observe('click', function(){
            unselect(this);
            List.next();
        });
        
        $('pagination_next').observe('mouseout', removeClassOver);
        $('pagination_next').observe('mouseover', addClassOver);

        $('pagination_prev').observe('click', function(){
            unselect(this);
            List.prev();
        });

        $('pagination_prev').observe('mouseout', removeClassOver);
        $('pagination_prev').observe('mouseover', addClassOver);
    };
    
    this.shutdown = function()
    {
        $('pagination').hide();
    };

    this.updateDisplay = function()
    {
        if ($('pagination_prev').hasClassName('unactive')){
            $('pagination_prev').removeClassName('unactive')
        }else
        if (List.page == 0){
            $('pagination_prev').addClassName('unactive');
        }

        if ($('pagination_next').hasClassName('unactive')){
            $('pagination_next').removeClassName('unactive')
        }else
        if (List.page == Math.ceil(Data.getTotal() / List.itemByPage) - 1){
            $('pagination_next').addClassName('unactive');
        }
        
        $('pagination_count').update((List.page + 1) + '/' + Math.ceil(Data.getTotal() / List.itemByPage));
    };
}
