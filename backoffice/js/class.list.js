function List()
{
    this.page;
    this.itemByPage = 25;
    this.itemList;
    this.itemOpened = 0;
    this.isOpen;
    this.maxSearchResult = 20;

    this.init = function()
    {
        $('list').show();
        $('list_tool').show();
        $('list_search').show();
        $('pagination').show();

        this.page = 0;
        this.isOpen = true;
        this.itemOpened = 0;
/*         this.reorder(); */
        this.showPage();

        // search input
        $('list_search_input').observe('keydown', function(event){
            List.search(event);
        });
        $('list_search_input').value = '';
    };

    this.shutdown = function()
    {
        $('list').hide();
        $('list_tool').hide();
        $('list_search').hide();

        Data.flush();
        this.itemList = new Array();
        this.isOpen = false;
    };

    this.showPage = function()
    {
        // empty list
        $('list').update();

        // fill list with page's elements
        var total = (this.itemByPage < Data.getTotal())?this.itemByPage:Data.getTotal();
        for (var i = 0; i < total; i ++){
            var e = (this.page * this.itemByPage) + i;
            if (e > Data.getTotal() - 1){
                break;
            }

            // create a new element
            this.itemList[i] = new Item(e);
            this.itemList[i].init();

            $('list').insert(this.itemList[i].container);
        }

        Pagination.updateDisplay();
    };

    this.next = function()
    {
        if (this.page < Data.getTotal() / this.itemByPage - 1){
            this.page ++;
            this.showPage();
        }
    };

    this.prev = function()
    {
        if (this.page > 0){
            this.page --;
            this.showPage();
        }
    };

    /* sort functions
    each sort function takes two parameters, a and b
    you are comparing a[0] and b[0] */
    this.sort_numeric = function(a,b) {
        a = a.toString();
        b = b.toString();
        aa = parseFloat(a[0].replace(/[^0-9.-]/g,''));
        if (isNaN(aa)) aa = 0;
        bb = parseFloat(b[0].replace(/[^0-9.-]/g,''));
        if (isNaN(bb)) bb = 0;
        return aa-bb;
    };

    this.sort_alpha = function(a,b) {
        if (a[0]==b[0]) return 0;
        if (a[0]<b[0]) return -1;
        return 1;
    };

    this.sort_ialpha = function(a,b) {
        a = (typeof a[0] == 'string') ? a[0].toLowerCase() : a[0];
        b = (typeof b[0] == 'string') ? b[0].toLowerCase() : b[0];
        if (a==b) return 0;
        if (a<b) return -1;
        return 1;
    };

    this.sort_ddmm = function(a,b) {
        mtch = a[0].match(sorttable.DATE_RE);
        y = mtch[3]; m = mtch[2]; d = mtch[1];
        if (m.length == 1) m = '0'+m;
        if (d.length == 1) d = '0'+d;
        dt1 = y+m+d;
        mtch = b[0].match(sorttable.DATE_RE);
        y = mtch[3]; m = mtch[2]; d = mtch[1];
        if (m.length == 1) m = '0'+m;
        if (d.length == 1) d = '0'+d;
        dt2 = y+m+d;
        if (dt1==dt2) return 0;
        if (dt1<dt2) return -1;
        return 1;
    };

    this.sort_mmdd = function(a,b) {
        mtch = a[0].match(sorttable.DATE_RE);
        y = mtch[3]; d = mtch[2]; m = mtch[1];
        if (m.length == 1) m = '0'+m;
        if (d.length == 1) d = '0'+d;
        dt1 = y+m+d;
        mtch = b[0].match(sorttable.DATE_RE);
        y = mtch[3]; d = mtch[2]; m = mtch[1];
        if (m.length == 1) m = '0'+m;
        if (d.length == 1) d = '0'+d;
        dt2 = y+m+d;
        if (dt1==dt2) return 0;
        if (dt1<dt2) return -1;
        return 1;
    };

    this.reorder = function() {
        var comp_func = this['sort_' + TPL.getParam('sortFunc')];
        var orderBy = TPL.getParam('orderBy');

        // A stable sort function to allow multi-level sorting of data
        // see: http://en.wikipedia.org/wiki/Cocktail_sort
        // thanks to Joseph Nahmias
        var b = 0;
        var t = Data.getTotal() - 1;
        var swap = true;

        while(swap) {
            swap = false;
            for(var i = b; i < t; ++i) {
                if ( comp_func(Data.get(orderBy, i), Data.get(orderBy, i+1)) > 0 ) {
                    var q = Data.grab(i);
                    Data.replace(Data.grab(i+1), i);
                    Data.replace(q, i+1);
                    swap = true;
                }
            }
            t--;

            if (!swap) break;

            for(var i = t; i > b; --i) {
                if ( comp_func(Data.get(orderBy, i), Data.get(orderBy, i-1)) < 0 ) {
                    var q = Data.grab(i);
                    Data.replace(Data.grab(i-1), i);
                    Data.replace(q, i-1);
                    swap = true;
                }
            }
            b++;
        }
    };

    this.search = function(e)
    {
        var keyCode = e.keyCode || e.which;

        if (keyCode == 13){
            var q = Event.element(e).value;
            if (q.blank()){
                $('pagination').show();
                this.showPage();
            }else{
                $('list').update();
                $('pagination').hide();

                var n = 0;
                var noMatch = new Array();

                for (var i = 0; i < Data.getTotal(); i ++){
                    if (n == this.maxSearchResult){
                        $('list').insert(new Element('li', {
                            'class': 'info'
                        }).update('...'));

                        return false;
                        break;
                    }

                    if (Data.get(TPL.getParam('previewField'), i).substring(0, q.length).match(new RegExp('^' + q + '$', 'i'))){
                        this.itemList[i] = new Item(i);
                        this.itemList[i].init();

                        $('list').insert(this.itemList[i].container);

                        n ++;
                    }else{
                        noMatch.push(i);
                    }
                }

                for (var i = 0; i < noMatch.length; i ++){
                    if (n == this.maxSearchResult){
                        $('list').insert(new Element('li', {
                            'class': 'info'
                        }).update('...'));

                        return false;
                        break;
                    }

                    if (Data.get(TPL.getParam('previewField'), noMatch[i]).match(new RegExp(q, 'i'))){
                        this.itemList[noMatch[i]] = new Item(noMatch[i]);
                        this.itemList[noMatch[i]].init();

                        $('list').insert(this.itemList[noMatch[i]].container);

                        n ++;
                    }
                }
            }

            return false;
        }
    };
}
