function simpleDate()
{
    this.n;
    this.param;
    this.date;

    this.dayOfWeek;
    this.dayList;
    this.monthList;
    this.yearList;
    this.hourList;
    this.minuteList;

    this.monthName = {
        0: 'Janvier',
        1: 'Févrié',
        2: 'Mars',
        3: 'Avril',
        4: 'Mai',
        5: 'Juin',
        6: 'Juillet',
        7: 'Août',
        8: 'Séptembre',
        9: 'Octobre',
        10: 'Novembre',
        11: 'Décembre'
    };

    this.dayName = {
        0: 'Lundi',
        1: 'Mardi',
        2: 'Mercredi',
        3: 'Jeudi',
        4: 'Vendredi',
        5: 'Samedi',
        6: 'Dimanche'
    };

    this.shutdown = function()
    {
        $('tool_' + this.n).remove();
        this.n = null;
        this.param = null;
    };

    this.create = function(n, param)
    {
        this.n = n;
        this.param = param;

        Data.seek(this.n);

        var container = new Element('form', {
            'id': 'tool_' + this.n,
            'class': 'simpleDate'
        });

        // get item's date or today's date if null
        this.date = new Date();
        if (Data.get(this.param.field) != 0){
            this.date.setTime(Data.get(this.param.field) * 1000);
        }

        // create feilds
        this.dayOfWeek = Cache.doElement('simpleDate.dayOfWeek', function(){
            return new Element('span', {
                'class': 'dayName'
            });
        });

        this.dayList = Cache.doElement('simpleDate.dayList', function(){
            var e = new Element('select').writeAttribute('name', 'day');
            for (var i = 1; i <= 31; i ++){
                e.insert(new Element('option', {'value': i}).update(i));
            }
            return e;
        });
        this.dayList.observe('change', this.saveDate.bind(this));

        this.monthList = Cache.doElement('simpleDate.monthList', function(){
            var e = new Element('select').writeAttribute('name', 'month');
            for (var i = 0; i < 12; i ++){
                e.insert(new Element('option', {'value': i}).update(this.monthName[i]));
            }
            return e;
        }.bind(this));
        this.monthList.observe('change', this.saveDate.bind(this));

        this.yearList = Cache.doElement('simpleDate.yearList', function(){
            var e = new Element('select').writeAttribute('name', 'year');
            for (var i = 1970; i < 2100; i ++){
                e.insert(new Element('option', {'value': i}).update(i));
            }
            return e;
        });
        this.yearList.observe('change', this.saveDate.bind(this));

        this.hourList = Cache.doElement('simpleDate.hourList', function(){
            var e = new Element('select').writeAttribute('name', 'hour');
            for (var i = 0; i < 24; i ++){
                e.insert(new Element('option', {'value': i}).update(i));
            }
            return e;
        });
        this.hourList.observe('change', this.saveDate.bind(this));

        this.minuteList = Cache.doElement('simpleDate.minuteList', function(){
            var e = new Element('select').writeAttribute('name', 'minute');
            for (var i = 0; i < 60; i ++){
                e.insert(new Element('option', {'value': i}).update(i));
            }
            return e;
        });
        this.minuteList.observe('change', this.saveDate.bind(this));

        container.insert(Cache.newElement('label').update(param.label + ' :'));
        container.insert(this.dayList);
        container.insert(this.monthList);
        container.insert(this.yearList);
        container.insert(Cache.newElement('span').update('å'));
        container.insert(this.hourList);
        container.insert(this.minuteList);
        container.insert(this.dayOfWeek);

        $('tool').insert(container);

        this.selectDate();
    };

    this.remove = function()
    {
    };

    this.saveDate = function()
    {
        this.date = new Date(
            this.yearList.value,
            this.monthList.value,
            this.dayList.value,
            this.hourList.value,
            this.minuteList.value,
            0
        );

        this.selectDate();
    };

    this.selectDate = function()
    {
        this.dayOfWeek.update(this.dayName[(this.date.getDay()+6)%7]);
        this.dayList.value = this.date.getDate();
        this.monthList.value = this.date.getMonth();
        this.yearList.value = this.date.getFullYear();
        this.hourList.value = this.date.getHours();
        this.minuteList.value = this.date.getMinutes();
    };

    this.save = function()
    {
        Edit.setSaveData(this.param.field, this.date.getTime() / 1000);
    };
}
