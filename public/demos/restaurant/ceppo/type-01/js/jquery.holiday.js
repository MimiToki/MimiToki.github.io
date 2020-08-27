
(function($) {
	var settings = {};
	var css = {};
	var holidays = {};
	var prepared = {};
	
	var methods = {
		init : function( options ) {
			if (!$.fn['datepicker']) {
				return this;
			}

			settings = $.extend( {
				'css': [
					{'day':0, 'class':'day-sunday'}, 
					{'day':6, 'class':'day-saturday'}, 
					{'day':'holiday', 'class':'day-holiday'}, 
					{'day':'anniversary', 'class':'day-anniversary'} 
				],
				'anniversary': []
			}, options);

			$.each(settings.css, function(key, val) {
				if (typeof val['class']!="undefined") {
					css[val['day']] = val['class'];
				}
			});

			var attr;
			$.each (settings.anniversary, function(key, val){
				attr = {};
				attr['name'] = val['name'];
				if (typeof val['class']!="undefined") {
					attr['class'] = val['class'];
				} else if (typeof css['anniversary']!="undefined") {
					attr['class'] = css['anniversary'];
				}
				holidays[val['date']] = attr;
			});

			return this.each(function() {
				if (typeof $(this).datepicker!="function") {
					return true;
				}
				if ($(this).data("holiday")) {
					return true;
				}
				$(this).data("holiday", {init: true});

				$(this).datepicker("option", "beforeShowDay", function(day) {
					var $self = $.fn.holiday;
					var attr = $self("attr", day);
					var dow = day.getDay();
					var title = "";
					if (typeof attr['name']!="undefined") {
						title = attr['name'];
					}
					var style = "";
					if (title && typeof attr['class']!="undefined") {
						style = attr['class']; 
						return false; 
					} else if (dow==0) {
						return false; 
					} else if (typeof css[dow]!="undefined") {
						style = css[dow]; 
					}

					return [true, style, title];
				});
			});

		},

		destroy : function( ) {
			return this.each(function() {
				if (typeof $(this).datepicker!="function") {
					return true;
				}
				$(this).datepicker("option", "beforeShowDay", null);
				$(this).removeData("holiday");
			});
		},

		attr : function( day ) {
			if (typeof day=="string") {
				var match = day.match(/^(20\d{2})(\d{2})(\d{2})$/);
				if (match) {
					match.shift();
					day = new Date(match.join("/"));
				} else {
					day = new Date(day);
				}
			}
			var Y = day.getFullYear();
			var M = padzero(day.getMonth()+1);
			if (typeof prepared[Y+M]=="undefined") {
				prepare(Y, M);
			}
			var YMD = day.getFullYear()+padzero(day.getMonth()+1)+padzero(day.getDate());
			if (typeof holidays[YMD]!="undefined") {
				return holidays[YMD];
			} else {
				return {};
			}
		},

		name : function( day ) {
			var attr = $.fn.holiday('attr', day);
			if (typeof attr['name']=="undefined") {
				return "";

			} else {
				return attr['name'];
			}
		},

		list : function() {
			return holidays;
		},
		
		setRegionData : function(obj) {
			if (typeof obj=="object") {
				if (typeof obj['annual']=="object") {
					annual = obj['annual'];
				}
				if (typeof obj['individual']=="object") {
					individual = obj['individual'];
				}
				if (typeof obj['words']=="object") {
					words = obj['words'];
				}
			}
		}
	};

	$.fn.holiday = function( method ) {
		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.holiday' );
		}
	};

	var padzero = function(val) {
		return ('0'+val).slice(-2);
	}

	var prepare = function(Y, M) {
		if (typeof prepared[Y+M]!="undefined") {
			return true;
		}

		var attr, substitutes = {};
		if (typeof annual[M]!="undefined") {
			var d, dayyear;
			$.each(annual[M], function(key, val){
				d = null;
				if (isFinite(val['day'])) {
					d = val['day'];
				} else if (val['day']=="spring equinox") {
					d = parseInt(20.69115 + (Y - 2000) * 0.2421904 - parseInt((Y - 2000) / 4));
				} else if (val['day']=="autumnal equinox") {
					d = parseInt(23.09 + (Y - 2000) * 0.2421904 - parseInt((Y - 2000) / 4));
				} else if (val['day'].match(/\//)) {
					d = padzero(nthday(Y, M, val['day']));
				} else if (val.day.match(/\>/)) {
					dayyear = val['day'].split('>');
					if (Y>=dayyear[1]) {
						d = padzero(dayyear[0]);
					}
				}

				if (d==null) {
					return true; //continue;
				}

				attr = {};
				attr['name'] = val['name'];
				if (typeof val['class']!="undefined") {
					attr['class'] = val['class'];
				} else if (typeof css['holiday']!="undefined") {
					attr['class'] = css['holiday'];
				} else {
					attr['class'] = "";
				}

				holidays[Y + M + d] = attr;

				d = new Date(Y, M-1, d);
				if (d.getDay()==0) {
					d = padzero(d.getDate() + 1);
					attr['name'] = words['substitute']+"("+val['name']+")";
					substitutes[Y +"/"+ M +"/"+ d] = attr;
				}
			});

			$.each (substitutes, function(key, val) {
				key = new Date(key);
				d = key.getFullYear()+padzero(key.getMonth()+1)+padzero(key.getDate());
				while (typeof holidays[d]!="undefined") {
					key.setDate(key.getDate() + 1);
					d = key.getFullYear()+padzero(key.getMonth()+1)+padzero(key.getDate());
				}
				holidays[d] = val;
			});
		}

		if (typeof individual[Y+M]!="undefined") {
			$.each(individual[Y+M], function(key, val) {
				attr = {};
				attr['name'] = val['name'];
				if (typeof val['class']!="undefined") {
					attr['class'] = val['class'];
				} else if (typeof css['holiday']!="undefined") {
					attr['class'] = css['holiday'];
				} else {
					attr['class'] = "";
				}
				holidays[Y+M+val.day] = attr;
			});
		};

		prepared[Y+M] = true;
		return true;
	}

	var nthday = function(y, m, val) {
		var dayOfWeek = val.split('/');
		var firstDay = new Date(y, m - 1, 1);
		var adjust = dayOfWeek[1] - firstDay.getDay();
		if (adjust < 0)
			adjust += 7;
		return 1 + adjust + (dayOfWeek[0] -1 ) * 7;
	}

	var annual = {
		'01': [{'day':'01','name':'Anniversary-Name'}, 
			   {'day':'02','name':'Anniversary-Name'},
			   {'day':'03','name':'Anniversary-Name'}],
		'02': [{'day':'11','name':'Anniversary-Name'}],
		'03': [{'day':'spring equinox','name':'Anniversary-Name'}],
		'04': [{'day':'29','name':'Anniversary-Name'}],
		'05': [{'day':'03','name':'Anniversary-Name'}, 
			   {'day':'04','name':'Anniversary-Name'}, 
			   {'day':'05','name':'Anniversary-Name'}],
		'07': [{'day':'3/1','name':'Anniversary-Name'}],
		'08': [{'day':'13>2018','name':'Anniversary-Name'}], 
		'09': [{'day':'3/1','name':'Anniversary-Name'}, 
			   {'day':'autumnal equinox','name':'Anniversary-Name'}],
		'10': [{'day':'2/1','name':'Anniversary-Name'}],
		'11': [{'day':'03','name':'Anniversary-Name'}, 
			   {'day':'23','name':'Anniversary-Name'}],
		'12': [{'day':'29','name':'Anniversary-Name'},
			   {'day':'30','name':'Anniversary-Name'},
			   {'day':'31','name':'Anniversary-Name'}]
	};
	var individual = {
		'201509': [{'day':'22','name':'Anniversary-Name'}],
		'202609': [{'day':'22','name':'Anniversary-Name'}], 
		'203209': [{'day':'21','name':'Anniversary-Name'}], 
		'203709': [{'day':'22','name':'Anniversary-Name'}] 
	};
	var words = {
		'substitute': 'test'
	};
	
})(jQuery);