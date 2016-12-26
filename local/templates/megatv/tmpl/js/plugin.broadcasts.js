(function($){
	'use strict';
	var DATE_START_TITLE = "date_start";
	var DATE_END_TITLE = "date_end";
	var NATIVE_DATE_START_TITLE = "native_date_start";
	var NATIVE_DATE_END_TITLE = "native_date_end";
	var DATE_DIFF_TITLE = "date_diff";
	var ID_TITLE = "id";
	var CHANNEL_ID_TITLE = "channel_id";
	var DATES_TITLE = "DATES";
	var COLUMN_TITLE = "column";
	var CHANNEL_ID_TITLE = "channel_id";
	var NAME_TITLE = "name";
	var IMGS_TITLE = "images";
	var IMG_DOUBLE_TITLE = "double";
	var IMG_DOUBLE_BAD_TITLE = "double_bad";
	var IMG_HALF_TITLE = "half";
	var IMG_HALF_BAD_TITLE = "half_bad";
	var IMG_ONE_TITLE = "one";
	var IMG_ONE_BAD_TITLE = "one_bad";
	var LINK_TITLE = "link";
	var TIME_TITLE = "time";
	var ON_AIR_TITLE = "on_air";
	var CHANNELS_TITLE = "CHANNELS";
	var DETAIL_PAGE_URL_TITLE = "DETAIL_PAGE_URL";
	var ICON_TITLE = "ICON";

	var PATTERN = "YYYY-MM-DD HH:mm:ss";

	var Broadcasts = function (options) {
		var self = this;
		$.each(options, function(key, value){
		  self[key] = value;
		});
		self.swiper = null;
	}

	Broadcasts.prototype = {
		initialize: function(){
			var self = this;
			self.timeNow = self.JSONParams[TIME_TITLE.toUpperCase()];
		    self.$wrapper = $(".swiper-wrapper");
		    var broadcasts = null;
			var object = self.offsetToOneObj(self.JSONParams[DATES_TITLE]);
			self.initChannels(object.channels);
		    broadcasts = self.correctBroadcasts(object.broadcasts);
		    var broadcastsView = self.viewBroadcasts(broadcasts);
			self.preInit();
			var momentNow = moment(self.timeNow);
			var weekday = self.config.weekdays[(momentNow.toString()).replace(/(.{3}).*/, "$1")];
			var hours = self.timeNow.replace(/.*\s([0-9]{2}\:[0-9]{2}).*/,"$1");
			var dayMarkTmpl = _.template($("#endDayMarkTmpl").html());
			var outputMark = dayMarkTmpl({
				endDayTitle: weekday + " " + momentNow.format("DD.MM.YYYY")
			});
			broadcastsView += outputMark;

		    setTimeout(function(){
		    	var json = _.clone(self.JSONParams);
		    	json[TIME_TITLE] = "2016-09-14 13:43:55";
		    	self.addDay(json);
		    },6000);

		    self.closePreloader();
		    self.$wrapper.html(broadcastsView);
		    setTimeout(function(){
	    		self.swiper = self.initSwiper();
	        	self.fixedTimeline(self.$wrapper, weekday + " " + hours);
				self.postInit();
				broadcastsView = null;
		    },1000);
		},

		initChannels: function(objChannels){
			var self = this;
		    self.channels = self.JSONParams[CHANNELS_TITLE];
		    self.channelsNum = objChannels;
		    var channelsView = self.viewChannels();
		    $(".channels__wrapper").html(channelsView);
		    setTimeout(function(){
		    	channelsView = null;
		    },500);
		},

		convertDate: function (date, noTime){
		    if(!noTime) noTime = "$4";
			date = date.replace(/([0-9]{2})\.([0-9]{2})\.([0-9]{4})\s(.*)/,"$3-$2-$1 " + noTime);
			return date;
		},

		isEmpty: function (v){
		    return _.isNull(v) || _.isUndefined(v) || _.isNaN(v) || v === false; 
		},

		getDiffFromDateStartEnd: function (start, end){
		    if(start > end) return "-00:00:00";
		    var dateStart = moment(start);
		    var dateEnd = moment(end);
		    return dateEnd.subtract({
		        "year" : dateStart.year(), 
		        "months" : dateStart.month() + 1, 
		        "days" : dateStart.days(), 
		        "hours" : dateStart.hours(), 
		        "minutes" : dateStart.minutes(),
		        "seconds" : dateStart.seconds()
		    }).format("HH:mm:ss");
		},

		offsetToOneObj: function (currArray){
			var self = this;
		    var object = {
		        broadcasts: [],
		        channels: []
		    };
		    for(var key in currArray){
		        var val = currArray[key];
		        for(var k in val){
		            var v = val[k];
		            object.channels.push(k);
		            for (var i = 0; i < v.length; i++) {
		                var start = currArray[key][k][i][DATE_START_TITLE] = self.convertDate(v[i][DATE_START_TITLE]);
		                var end = currArray[key][k][i][DATE_END_TITLE] = self.convertDate(v[i][DATE_END_TITLE]);
		                var startNextDay = moment().add(1, "days").startOf("days").format(PATTERN);
		                if(start > end || end >= startNextDay) {
		                    end = currArray[key][k][i][DATE_END_TITLE] = moment().endOf("days").format(PATTERN);
		                }
		                var diff = self.getDiffFromDateStartEnd(start, end);
		                currArray[key][k][i][DATE_DIFF_TITLE] = diff;
		                object.broadcasts.push(currArray[key][k][i]);
		            }
		        };
		    };
		    return object;
		},

		collectObject: function (broadcasts){
		    var array = {};
		    function recursion(obj){
		        var curr = obj.current;
		        var elDateStart = curr[DATE_START_TITLE];
		        var elDateEnd = curr[DATE_END_TITLE];
		        if(broadcasts.length > 0){
		            for(var i = 0; i < broadcasts.length; i++){
		                var dateStart = broadcasts[i][DATE_START_TITLE];
		                var dateEnd = broadcasts[i][DATE_END_TITLE];
		                var dateDiff = broadcasts[i][DATE_DIFF_TITLE];
		                if(dateDiff > "00:10:00"){
		                    if((elDateStart <= dateStart && elDateEnd >= dateEnd) && broadcasts[i][ID_TITLE] != curr[ID_TITLE]){
		                        var broadcast = broadcasts[i];
		                        broadcasts.splice(i, 1);
		                        if(_.isUndefined(obj.children[broadcast[ID_TITLE]]))
		                            obj.children[broadcast[ID_TITLE]] = {
		                                current: broadcast,
		                                children: []
		                            };
		                        recursion(obj.children[broadcast[ID_TITLE]]);
		                    }
		                }
		            }
		        }
		    }
		    for(var i = 0; i < broadcasts.length; i++){
		        var broadcast = broadcasts[i];
		        broadcasts.splice(i, 1);
		        array[broadcast[ID_TITLE]] = {
		            current: broadcast,
		            children: []
		        };
		        recursion(array[broadcast[ID_TITLE]]);
		    };
		    return array;
		},

		mathTimes: function (dates, symb, pattern){
		    if(!pattern) pattern = "HH:mm:ss";
		    var date1 = moment(dates[0]);
		    var date2 = dates[1];

		    if(symb) return date1.add(date2).format(pattern);
		    else return date1.subtract(date2).format(pattern);
		},

		roundTime: function (date, direction, type){
		    var hours, momentDate;
		    date = moment(date);
		    type = (!type) ? [] : type;
		    if(type.length === 0) {
		        type[0] = "format";
		        type[1] = PATTERN;
		    }
		    if(direction){
		        momentDate = (date.endOf("hours")).add(1, "seconds");
		        hours = momentDate.hours();
		        if(hours % 2 !== 0) momentDate = momentDate.add(1, "hours");
		        if(type[0] === "hours") return momentDate.hours();
		        else if(type[0] === "format") return momentDate.format(type[1]); 
		        else return momentDate;
		    }else{
		        momentDate = date.startOf("hours");
		        hours = momentDate.hours();
		        if(hours % 2 !== 0) momentDate = momentDate.subtract(1, "hours");
		        if(type[0] === "hours") return momentDate.hours();
		        else if(type[0] === "format") return momentDate.format(type[1]); 
		        else return momentDate;
		    }
		},

		parseBroadcastByTime: function (broadcast){
			var self = this;
		    var beginDay = createMoment(0).format(PATTERN);
		    function createMoment(num){
		        return moment().minutes(0).seconds(0).hours(num);
		    }

		    function format(momentObj, pattern){
		        if(!pattern) pattern = PATTERN;
		        return momentObj.format(pattern);
		    }

		    function collectArray(item, numStart){
		        var array = [];
		        var startTime, endTime, bigArray = false, column = 0, diffFull, diff, offset, limit;
		        var startPart = createMoment(0);
		        item = _.clone(item);
		        var start = startTime = item[DATE_START_TITLE];
		        var end = endTime = item[DATE_END_TITLE];
		        if(startPart > start)
		            start = startPart;
		        diffFull = self.getDiffFromDateStartEnd(start, end);
		        if(diffFull < "00:15:00") return [];
		        offset = self.roundTime(moment(start), false, ["hours"]);
		        limit = self.roundTime(moment(end), true, ["hours"]);
		        if(limit === 0) limit = 24;
		        var early30m, early15m, later30m, later15m, early, later, current, broadcast, push = false, stop = false, diffNext;
		        for (var i = offset; i < limit; i++) {
		            current = format(createMoment(i));
		            early = format(createMoment(i).subtract(1, "hours"));
		            later = format(createMoment(i).add(1, "hours"));
		            early30m = self.mathTimes([early, { minutes: 30 }], false, PATTERN);
		            later30m = self.mathTimes([later, { minutes: 30 }], true, PATTERN);
		            broadcast = _.clone(item);
		            broadcast[COLUMN_TITLE] = i;
		            if(i % 2){
		                diff = self.getDiffFromDateStartEnd(start, later);
		                diffNext = self.getDiffFromDateStartEnd(later, end);
		                push = false; stop = false;
		                if(diffFull >= "01:00:00"){
		                    startTime = ((startTime >= early30m && startTime <= early) ? start : early) < start ? start : early;
		                    startTime = (start >= early30m && start <= early) ? start : startTime;
		                    endTime = ((endTime <= later30m && endTime > later) ? end : later) > end ? end : later;
		                    endTime = (end <= later30m && end >= later) ? end : endTime;
		                    diff = self.getDiffFromDateStartEnd(startTime, endTime);
		                    if(diff > "00:30:00"){
		                        push = true;
		                    }
		                }else{
		                    startTime = start;
		                    endTime = end;
		                    if(start <= later && later <= end && end <= later30m){
		                        if(diff < diffNext){
		                            broadcast[COLUMN_TITLE] = i + 2;
		                        }
		                        stop = push = true;
		                    }else if((early <= start && start < later) && end <= later){
		                        stop = push = true;
		                    }else if(early <= start && current >= start && later30m >= end){
		                        stop = push = true;
		                    }else if((early30m <= start && early >= start) && end <= later){
		                        stop = push = true;
		                    }
		                }

		                diff = self.getDiffFromDateStartEnd(startTime, endTime);
		                broadcast[DATE_START_TITLE] = startTime;
		                broadcast[DATE_END_TITLE] = endTime;

		                if(startTime <= self.timeNow && endTime >= self.timeNow) 
		                    broadcast[ON_AIR_TITLE] = true;
		                broadcast[DATE_DIFF_TITLE] = diff;

		                column++;
		            }else{
		                if(start <= self.timeNow && end >= self.timeNow) 
		                    broadcast[ON_AIR_TITLE] = true;
		            }
		            if(push && diff > "00:00:00") array.push(broadcast);
		            if(stop) break;
		        }
		        return array;
		    }

		    
		    return collectArray(broadcast);
		},

		pushToColumns: function (broadcasts){
			var self = this;
			var channels = self.channelsNum;
		    var array = [];
		    var channel, channel_id, b_col, b_channel, col;
		    for (col = 0; col < 24; col++) {
		        if(col % 2){
		            array[col] = [];
		            for (channel = 0; channel < channels.length; channel++) {
		                channel_id = channels[channel];
		                array[col][channel_id] = [];
		                for (var b = 0; b < broadcasts.length; b++) {
		                    b_col = broadcasts[b][COLUMN_TITLE];
		                    b_channel = broadcasts[b][CHANNEL_ID_TITLE];
		                    if(b_col === col && b_channel === channel_id){
		                        array[col][channel_id].push(broadcasts[b]);
		                    }
		                }
		            }
		        }
		    }
		    return array;
		},

		viewBroadcasts: function (broadcasts){
			var self = this;
		    var i = 0;
		    var divSetInit = '<div class="bs-container__set bs-set" />';
		    var divColumnInit = '<div class="bs-container__column swiper-slide" />';
		    var divWrapInit = '<div />';
		    var doubleXClass = 'bs-container__set--double-x';
		    var doubleYClass = 'bs-container__set--double-y';
		    var doubleClass = 'bs-container__column--double';
		    var origin = self.origin;
		    var channel, channelVal, countB, divSetMain, divSetInner, divSetInner2, divColumn, col, broadcast, divSet, tmpl, isSetInner, output, colVal, finalObj;
		    var divWrapper = $(divWrapInit);
		    for (col in broadcasts) {
		        colVal = broadcasts[col];
		        divColumn = $(divColumnInit).addClass(doubleClass);
		        for (channel in colVal) {
		            channelVal = colVal[channel];
		            countB = channelVal.length;
		            divSetMain = $(divSetInit);
		            divSetInner = $(divSetInit);
		            divSetInner2 = $(divSetInit);
		            divSetMain.addClass(doubleXClass);
		            for (var b in channelVal) {
		                broadcast = channelVal[b];
		                tmpl = _.template($("#broadcastTmpl").html());
		                output = {
		                    title : broadcast[NAME_TITLE],
		                    link : broadcast[LINK_TITLE],
		                    time : broadcast[DATE_START_TITLE].replace(/.*\s([0-9]{2}\:[0-9]{2}).*/,"$1"),
		                    onAir : broadcast[ON_AIR_TITLE],
		                    image : origin + broadcast[IMGS_TITLE][IMG_HALF_TITLE],
		                    blurImage : origin + broadcast[IMGS_TITLE][IMG_HALF_BAD_TITLE]
		                };
		                if(countB === 1){
		                    output.image = origin + broadcast[IMGS_TITLE][IMG_DOUBLE_TITLE];
		                    output.blurImage = origin + broadcast[IMGS_TITLE][IMG_DOUBLE_BAD_TITLE];
		                    finalObj = tmpl(output);
		                    divSetMain.removeClass(doubleXClass)
		                              .html(finalObj);
		                }else if(countB === 2){
		                    output.image = origin + broadcast[IMGS_TITLE][IMG_ONE_TITLE];
		                    output.blurImage = origin + broadcast[IMGS_TITLE][IMG_ONE_BAD_TITLE];
		                    finalObj = tmpl(output);
		                    divSetMain.append(finalObj);
		                }else if(countB === 3){
		                    if(b == 0){
		                        output.image = origin + broadcast[IMGS_TITLE][IMG_ONE_TITLE];
		                        output.blurimage = origin + broadcast[IMGS_TITLE][IMG_ONE_BAD_TITLE];
		                        finalObj = tmpl(output);
		                        divSetMain.append(finalObj);
		                    }else{
		                        finalObj = tmpl(output);
		                        divSetInner.addClass(doubleYClass)
		                                   .append(finalObj);
		                    }
		                }else if(countB === 4){
		                    if(b < 2){
		                        finalObj = tmpl(output);
		                        divSetInner.addClass(doubleYClass)
		                                   .append(finalObj);
		                    }else{
		                        finalObj = tmpl(output);
		                        divSetInner2.addClass(doubleYClass)
		                                    .append(finalObj);
		                    }
		                }
		            }
		            if(countB === 0){
		                tmpl = _.template($("#broadcastEmptyTmpl").html());
		                var emptyNum = col-1;
		                finalObj = tmpl({
		                    time: ((emptyNum < 10) ? "0" + emptyNum : emptyNum) + ":00"
		                });
		                divSetMain.removeClass(doubleXClass)
		                          .html(finalObj);
		            }
		            if($.trim(divSetInner.text()) != "") 
		                divSetMain.append(divSetInner);
		            if($.trim(divSetInner2.text()) != "") 
		                divSetMain.append(divSetInner2);
		            if($.trim(divSetMain.text()) != "") 
		                divColumn.append(divSetMain);
		            finalObj = divSetInner = divSetInner2 = divSetMain = null;
		        }
		        divWrapper.append(divColumn);
		        divColumn = null;
		    }
		    return divWrapper.html();
		},

		viewChannels: function (){
			var self = this;
			var channels = self.channels;
			var channelsNum = self.channelsNum;
		    var mainBlock = $("<div />");
		    var channelView, output;
		    for (var i = 0; i < channelsNum.length; i++) {
		        _.each(channels, function(v,k){
		            var id = v[ID_TITLE.toUpperCase()];
		            if(channelsNum[i] === id){
		                channelView = _.template($("#channelTmpl").html());
		                output = channelView({
		                    "id" : id,
		                    "link" : v[DETAIL_PAGE_URL_TITLE],
		                    "icon" : v[ICON_TITLE]
		                });
		                mainBlock.append(output);
		            }
		        });
		    }
		    return mainBlock.html();
		},

		correctBroadcasts: function (broadcasts){
			var self = this;
		    var returnArr = [];
		    for (var y = 0; y < broadcasts.length; y++) {
		        if(y >= 0){
		            var broadcast = self.parseBroadcastByTime(broadcasts[y]);
		            if(broadcast.length > 0) returnArr.push(broadcast);
		        }
		    }
		    returnArr = _.flatten(returnArr);
		    return self.pushToColumns(returnArr);
		},

		initSwiper: function (){
		    var swiper = new Swiper('.swiper-container', {
		            scrollbar: '.swiper-scrollbar',
		            slidesPerView: "auto",
		            scrollbarHide: true,
		            keyboardControl: true,
		            nextButton: '.swiper-button-next',
		            prevButton: '.swiper-button-prev',
		            spaceBetween: 0,
		            hashnav: true,
		            preloadImages: false,
		            lazyLoading: true,
		            lazyLoadingOnTransitionStart: true,
		            grabCursor: false,
		            freeMode: true
		        }); 
		    return swiper;
		},
		
		addSlides: function (slidesHTML, method){
			var self = this;
		    if(!method) method = "append";
		    var $div = $("<div />").html(slidesHTML);
		    var slides = [];
		    $div.children().each(function(){
		        var $this = $(this);
		        slides.push($this);
		    });
		    switch(method){
		        case "append":
		            self.swiper.appendSlide(slides);
		        break;

		        case "prepend":
		            self.swiper.prependSlide(slides);
		        break;
		    }
		},

		addDay: function (json){
			var self = this;
		    var broadcasts = null;
			var object = self.offsetToOneObj(json[DATES_TITLE]);
		    broadcasts = self.correctBroadcasts(object.broadcasts);
		    var broadcastsView = self.viewBroadcasts(broadcasts);
			var momentNow = moment(json[TIME_TITLE]);
			var weekday = self.config.weekdays[(momentNow.toString()).replace(/(.{3}).*/, "$1")];
			var hours = self.timeNow.replace(/.*\s([0-9]{2}\:[0-9]{2}).*/,"$1");
			var dayMarkTmpl = _.template($("#beginDayMarkTmpl").html());
			var outputMark = dayMarkTmpl({
				beginDayTitle: weekday + " " + momentNow.format("DD.MM.YYYY")
			});
			broadcastsView = outputMark + broadcastsView;
			var dayMarkTmpl = _.template($("#endDayMarkTmpl").html());
			var outputMark = dayMarkTmpl({
				endDayTitle: weekday + " " + momentNow.format("DD.MM.YYYY")
			});
			broadcastsView += outputMark;
		    
		    self.addSlides(broadcastsView);
		},

		fixedTimeline: function ($wrapper, today){
		    var tmpl = _.template($("#timelineTmpl").html());
		    var $timeline = $(tmpl({
		        today: today
		    }));
		    var $line = $timeline.find(".timeline__line");
		    var $title = $timeline.find(".timeline__title");
		    var $columns = $wrapper.children(".swiper-slide");
		    var left = 0;
		    for(var i = 0; i < $columns.length; i++){
		        var $this = $($columns[i]);
		        left += parseInt($this.width());
		        if($this.find(".broadcast__on-air").length > 0){
		            break;
		        }
		    };
		    $line.css("left", (left - 150));
		    $title.css("left", (left - 370));
		    $wrapper.prepend($timeline);
		    setTimeout(function(){
		        $wrapper = null;
		        $title = null;
		        $line = null;
		        $columns = null;
		        $timeline = null;
		    },500);
		},
		closePreloader: function (){
		    var $loader = $(".broadcasts-loader");
		    $loader.addClass("broadcasts-loader--loaded");
		}
	};

	$.fn.Broadcasts = function(method, options) {
	  var options = $.extend({
          config: {},
          origin: location.origin,
          JSONParams: {},
          timeNow: moment().format(PATTERN),
	      preInit: function(){},
	      postInit: function(){}
	  }, options);

	  return this.each(function() {
	      var $this = $(this),
	          bsInit = new Broadcasts($.extend({
	            el: $this
	          },options));
	      if(method === undefined || method == "" || !method) {
	      	bsInit.initialize();
	      	return bsInit;
	      }else if(method in bsInit) {
	      	bsInit[method]();
	      	return bsInit;
	      }else {
	        console.error("Exception: Undefined method!");
	        return;
	      }
	  });

	};

})(jQuery);