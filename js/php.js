var fetch_unix_timestamp = function(){
	//return parseInt(new Date().getTime().toString().substring(0, 10));
	return Math.floor(new Date().getTime() / 1000);
}
var timestamp = fetch_unix_timestamp();

function mktime() {
  //  discuss at: http://phpjs.org/functions/mktime/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: baris ozdil
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: FGFEmperor
  // improved by: Brett Zamir (http://brett-zamir.me)
  //    input by: gabriel paderni
  //    input by: Yannoo
  //    input by: jakes
  //    input by: 3D-GRAF
  //    input by: Chris
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Marc Palau
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //  revised by: Theriault
  //        note: The return values of the following examples are
  //        note: received only if your system's timezone is UTC.
  //   example 1: mktime(14, 10, 2, 2, 1, 2008);
  //   returns 1: 1201875002
  //   example 2: mktime(0, 0, 0, 0, 1, 2008);
  //   returns 2: 1196467200
  //   example 3: make = mktime();
  //   example 3: td = new Date();
  //   example 3: real = Math.floor(td.getTime() / 1000);
  //   example 3: diff = (real - make);
  //   example 3: diff < 5
  //   returns 3: true
  //   example 4: mktime(0, 0, 0, 13, 1, 1997)
  //   returns 4: 883612800
  //   example 5: mktime(0, 0, 0, 1, 1, 1998)
  //   returns 5: 883612800
  //   example 6: mktime(0, 0, 0, 1, 1, 98)
  //   returns 6: 883612800
  //   example 7: mktime(23, 59, 59, 13, 0, 2010)
  //   returns 7: 1293839999
  //   example 8: mktime(0, 0, -1, 1, 1, 1970)
  //   returns 8: -1

  var d = new Date(),
    r = arguments,
    i = 0,
    e = ['Hours', 'Minutes', 'Seconds', 'Month', 'Date', 'FullYear'];

  for (i = 0; i < e.length; i++) {
    if (typeof r[i] === 'undefined') {
      r[i] = d['get' + e[i]]();
      r[i] += (i === 3); // +1 to fix JS months.
    } else {
      r[i] = parseInt(r[i], 10);
      if (isNaN(r[i])) {
        return false;
      }
    }
  }

  // Map years 0-69 to 2000-2069 and years 70-100 to 1970-2000.
  r[5] += (r[5] >= 0 ? (r[5] <= 69 ? 2e3 : (r[5] <= 100 ? 1900 : 0)) : 0);

  // Set year, month (-1 to fix JS months), and date.
  // !This must come before the call to setHours!
  d.setFullYear(r[5], r[3] - 1, r[4]);

  // Set hours, minutes, and seconds.
  d.setHours(r[0], r[1], r[2]);

  // Divide milliseconds by 1000 to return seconds and drop decimal.
  // Add 1 second if negative or it'll be off from PHP by 1 second.
  return (d.getTime() / 1e3 >> 0) - (d.getTime() < 0);
}

function date(format, timestamp) {
  var that = this;
  var jsdate, f;
  // Keep this here (works, but for code commented-out below for file size reasons)
  // var tal= [];
  var txt_words = [
    'Sun', 'Mon', 'Tues', 'Wednes', 'Thurs', 'Fri', 'Satur',
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];
  // trailing backslash -> (dropped)
  // a backslash followed by any character (including backslash) -> the character
  // empty string -> empty string
  var formatChr = /\\?(.?)/gi;
  var formatChrCb = function(t, s) {
    return f[t] ? f[t]() : s;
  };
  var _pad = function(n, c) {
    n = String(n);
    while (n.length < c) {
      n = '0' + n;
    }
    return n;
  };
  f = {
    // Day
    d: function() { // Day of month w/leading 0; 01..31
      return _pad(f.j(), 2);
    },
    D: function() { // Shorthand day name; Mon...Sun
      return f.l()
        .slice(0, 3);
    },
    j: function() { // Day of month; 1..31
      return jsdate.getDate();
    },
    l: function() { // Full day name; Monday...Sunday
      return txt_words[f.w()] + 'day';
    },
    N: function() { // ISO-8601 day of week; 1[Mon]..7[Sun]
      return f.w() || 7;
    },
    S: function() { // Ordinal suffix for day of month; st, nd, rd, th
      var j = f.j();
      var i = j % 10;
      if (i <= 3 && parseInt((j % 100) / 10, 10) == 1) {
        i = 0;
      }
      return ['st', 'nd', 'rd'][i - 1] || 'th';
    },
    w: function() { // Day of week; 0[Sun]..6[Sat]
      return jsdate.getDay();
    },
    z: function() { // Day of year; 0..365
      var a = new Date(f.Y(), f.n() - 1, f.j());
      var b = new Date(f.Y(), 0, 1);
      return Math.round((a - b) / 864e5);
    },

    // Week
    W: function() { // ISO-8601 week number
      var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3);
      var b = new Date(a.getFullYear(), 0, 4);
      return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
    },

    // Month
    F: function() { // Full month name; January...December
      return txt_words[6 + f.n()];
    },
    m: function() { // Month w/leading 0; 01...12
      return _pad(f.n(), 2);
    },
    M: function() { // Shorthand month name; Jan...Dec
      return f.F()
        .slice(0, 3);
    },
    n: function() { // Month; 1...12
      return jsdate.getMonth() + 1;
    },
    t: function() { // Days in month; 28...31
      return (new Date(f.Y(), f.n(), 0))
        .getDate();
    },

    // Year
    L: function() { // Is leap year?; 0 or 1
      var j = f.Y();
      return j % 4 === 0 & j % 100 !== 0 | j % 400 === 0;
    },
    o: function() { // ISO-8601 year
      var n = f.n();
      var W = f.W();
      var Y = f.Y();
      return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
    },
    Y: function() { // Full year; e.g. 1980...2010
      return jsdate.getFullYear();
    },
    y: function() { // Last two digits of year; 00...99
      return f.Y()
        .toString()
        .slice(-2);
    },

    // Time
    a: function() { // am or pm
      return jsdate.getHours() > 11 ? 'pm' : 'am';
    },
    A: function() { // AM or PM
      return f.a()
        .toUpperCase();
    },
    B: function() { // Swatch Internet time; 000..999
      var H = jsdate.getUTCHours() * 36e2;
      // Hours
      var i = jsdate.getUTCMinutes() * 60;
      // Minutes
      var s = jsdate.getUTCSeconds(); // Seconds
      return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
    },
    g: function() { // 12-Hours; 1..12
      return f.G() % 12 || 12;
    },
    G: function() { // 24-Hours; 0..23
      return jsdate.getHours();
    },
    h: function() { // 12-Hours w/leading 0; 01..12
      return _pad(f.g(), 2);
    },
    H: function() { // 24-Hours w/leading 0; 00..23
      return _pad(f.G(), 2);
    },
    i: function() { // Minutes w/leading 0; 00..59
      return _pad(jsdate.getMinutes(), 2);
    },
    s: function() { // Seconds w/leading 0; 00..59
      return _pad(jsdate.getSeconds(), 2);
    },
    u: function() { // Microseconds; 000000-999000
      return _pad(jsdate.getMilliseconds() * 1000, 6);
    },

    // Timezone
    e: function() { // Timezone identifier; e.g. Atlantic/Azores, ...
      // The following works, but requires inclusion of the very large
      // timezone_abbreviations_list() function.
      /*              return that.date_default_timezone_get();
       */
      throw 'Not supported (see source code of date() for timezone on how to add support)';
    },
    I: function() { // DST observed?; 0 or 1
      // Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
      // If they are not equal, then DST is observed.
      var a = new Date(f.Y(), 0);
      // Jan 1
      var c = Date.UTC(f.Y(), 0);
      // Jan 1 UTC
      var b = new Date(f.Y(), 6);
      // Jul 1
      var d = Date.UTC(f.Y(), 6); // Jul 1 UTC
      return ((a - c) !== (b - d)) ? 1 : 0;
    },
    O: function() { // Difference to GMT in hour format; e.g. +0200
      var tzo = jsdate.getTimezoneOffset();
      var a = Math.abs(tzo);
      return (tzo > 0 ? '-' : '+') + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
    },
    P: function() { // Difference to GMT w/colon; e.g. +02:00
      var O = f.O();
      return (O.substr(0, 3) + ':' + O.substr(3, 2));
    },
    T: function() { // Timezone abbreviation; e.g. EST, MDT, ...
      // The following works, but requires inclusion of the very
      // large timezone_abbreviations_list() function.
      /*              var abbr, i, os, _default;
      if (!tal.length) {
        tal = that.timezone_abbreviations_list();
      }
      if (that.php_js && that.php_js.default_timezone) {
        _default = that.php_js.default_timezone;
        for (abbr in tal) {
          for (i = 0; i < tal[abbr].length; i++) {
            if (tal[abbr][i].timezone_id === _default) {
              return abbr.toUpperCase();
            }
          }
        }
      }
      for (abbr in tal) {
        for (i = 0; i < tal[abbr].length; i++) {
          os = -jsdate.getTimezoneOffset() * 60;
          if (tal[abbr][i].offset === os) {
            return abbr.toUpperCase();
          }
        }
      }
      */
      return 'UTC';
    },
    Z: function() { // Timezone offset in seconds (-43200...50400)
      return -jsdate.getTimezoneOffset() * 60;
    },

    // Full Date/Time
    c: function() { // ISO-8601 date.
      return 'Y-m-d\\TH:i:sP'.replace(formatChr, formatChrCb);
    },
    r: function() { // RFC 2822
      return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
    },
    U: function() { // Seconds since UNIX epoch
      return jsdate / 1000 | 0;
    }
  };
  this.date = function(format, timestamp) {
    that = this;
    jsdate = (timestamp === undefined ? new Date() : // Not provided
      (timestamp instanceof Date) ? new Date(timestamp) : // JS Date()
      new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
    );
    return format.replace(formatChr, formatChrCb);
  };
  return this.date(format, timestamp);
}

function strtotime(text, now) {
  var parsed, match, today, year, date, days, ranges, len, times, regex, i, fail = false;

  if (!text) {
    return fail;
  }

  // Unecessary spaces
  text = text.replace(/^\s+|\s+$/g, '')
    .replace(/\s{2,}/g, ' ')
    .replace(/[\t\r\n]/g, '')
    .toLowerCase();

  // in contrast to php, js Date.parse function interprets:
  // dates given as yyyy-mm-dd as in timezone: UTC,
  // dates with "." or "-" as MDY instead of DMY
  // dates with two-digit years differently
  // etc...etc...
  // ...therefore we manually parse lots of common date formats
  match = text.match(
    /^(\d{1,4})([\-\.\/\:])(\d{1,2})([\-\.\/\:])(\d{1,4})(?:\s(\d{1,2}):(\d{2})?:?(\d{2})?)?(?:\s([A-Z]+)?)?$/);

  if (match && match[2] === match[4]) {
    if (match[1] > 1901) {
      switch (match[2]) {
        case '-':
          { // YYYY-M-D
            if (match[3] > 12 || match[5] > 31) {
              return fail;
            }

            return new Date(match[1], parseInt(match[3], 10) - 1, match[5],
              match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
          }
        case '.':
          { // YYYY.M.D is not parsed by strtotime()
            return fail;
          }
        case '/':
          { // YYYY/M/D
            if (match[3] > 12 || match[5] > 31) {
              return fail;
            }

            return new Date(match[1], parseInt(match[3], 10) - 1, match[5],
              match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
          }
      }
    } else if (match[5] > 1901) {
      switch (match[2]) {
        case '-':
          { // D-M-YYYY
            if (match[3] > 12 || match[1] > 31) {
              return fail;
            }

            return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
              match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
          }
        case '.':
          { // D.M.YYYY
            if (match[3] > 12 || match[1] > 31) {
              return fail;
            }

            return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
              match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
          }
        case '/':
          { // M/D/YYYY
            if (match[1] > 12 || match[3] > 31) {
              return fail;
            }

            return new Date(match[5], parseInt(match[1], 10) - 1, match[3],
              match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
          }
      }
    } else {
      switch (match[2]) {
        case '-':
          { // YY-M-D
            if (match[3] > 12 || match[5] > 31 || (match[1] < 70 && match[1] > 38)) {
              return fail;
            }

            year = match[1] >= 0 && match[1] <= 38 ? +match[1] + 2000 : match[1];
            return new Date(year, parseInt(match[3], 10) - 1, match[5],
              match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
          }
        case '.':
          { // D.M.YY or H.MM.SS
            if (match[5] >= 70) { // D.M.YY
              if (match[3] > 12 || match[1] > 31) {
                return fail;
              }

              return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
                match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
            }
            if (match[5] < 60 && !match[6]) { // H.MM.SS
              if (match[1] > 23 || match[3] > 59) {
                return fail;
              }

              today = new Date();
              return new Date(today.getFullYear(), today.getMonth(), today.getDate(),
                match[1] || 0, match[3] || 0, match[5] || 0, match[9] || 0) / 1000;
            }

            return fail; // invalid format, cannot be parsed
          }
        case '/':
          { // M/D/YY
            if (match[1] > 12 || match[3] > 31 || (match[5] < 70 && match[5] > 38)) {
              return fail;
            }

            year = match[5] >= 0 && match[5] <= 38 ? +match[5] + 2000 : match[5];
            return new Date(year, parseInt(match[1], 10) - 1, match[3],
              match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
          }
        case ':':
          { // HH:MM:SS
            if (match[1] > 23 || match[3] > 59 || match[5] > 59) {
              return fail;
            }

            today = new Date();
            return new Date(today.getFullYear(), today.getMonth(), today.getDate(),
              match[1] || 0, match[3] || 0, match[5] || 0) / 1000;
          }
      }
    }
  }

  // other formats and "now" should be parsed by Date.parse()
  if (text === 'now') {
    return now === null || isNaN(now) ? new Date()
      .getTime() / 1000 | 0 : now | 0;
  }
  if (!isNaN(parsed = Date.parse(text))) {
    return parsed / 1000 | 0;
  }

  date = now ? new Date(now * 1000) : new Date();
  days = {
    'sun': 0,
    'mon': 1,
    'tue': 2,
    'wed': 3,
    'thu': 4,
    'fri': 5,
    'sat': 6
  };
  ranges = {
    'yea': 'FullYear',
    'mon': 'Month',
    'day': 'Date',
    'hou': 'Hours',
    'min': 'Minutes',
    'sec': 'Seconds'
  };

  function lastNext(type, range, modifier) {
    var diff, day = days[range];

    if (typeof day !== 'undefined') {
      diff = day - date.getDay();

      if (diff === 0) {
        diff = 7 * modifier;
      } else if (diff > 0 && type === 'last') {
        diff -= 7;
      } else if (diff < 0 && type === 'next') {
        diff += 7;
      }

      date.setDate(date.getDate() + diff);
    }
  }

  function process(val) {
    var splt = val.split(' '), // Todo: Reconcile this with regex using \s, taking into account browser issues with split and regexes
      type = splt[0],
      range = splt[1].substring(0, 3),
      typeIsNumber = /\d+/.test(type),
      ago = splt[2] === 'ago',
      num = (type === 'last' ? -1 : 1) * (ago ? -1 : 1);

    if (typeIsNumber) {
      num *= parseInt(type, 10);
    }

    if (ranges.hasOwnProperty(range) && !splt[1].match(/^mon(day|\.)?$/i)) {
      return date['set' + ranges[range]](date['get' + ranges[range]]() + num);
    }

    if (range === 'wee') {
      return date.setDate(date.getDate() + (num * 7));
    }

    if (type === 'next' || type === 'last') {
      lastNext(type, range, num);
    } else if (!typeIsNumber) {
      return false;
    }

    return true;
  }

  times = '(years?|months?|weeks?|days?|hours?|minutes?|min|seconds?|sec' +
    '|sunday|sun\\.?|monday|mon\\.?|tuesday|tue\\.?|wednesday|wed\\.?' +
    '|thursday|thu\\.?|friday|fri\\.?|saturday|sat\\.?)';
  regex = '([+-]?\\d+\\s' + times + '|' + '(last|next)\\s' + times + ')(\\sago)?';

  match = text.match(new RegExp(regex, 'gi'));
  if (!match) {
    return fail;
  }

  for (i = 0, len = match.length; i < len; i++) {
    if (!process(match[i])) {
      return fail;
    }
  }

  // ECMAScript 5 only
  // if (!match.every(process))
  //    return false;

  return (date.getTime() / 1000);
}

function sprintf() {
  var regex = /%%|%(\d+\$)?([-+\'#0 ]*)(\*\d+\$|\*|\d+)?(\.(\*\d+\$|\*|\d+))?([scboxXuideEfFgG])/g;
  var a = arguments;
  var i = 0;
  var format = a[i++];

  // pad()
  var pad = function(str, len, chr, leftJustify) {
    if (!chr) {
      chr = ' ';
    }
    var padding = (str.length >= len) ? '' : new Array(1 + len - str.length >>> 0)
      .join(chr);
    return leftJustify ? str + padding : padding + str;
  };

  // justify()
  var justify = function(value, prefix, leftJustify, minWidth, zeroPad, customPadChar) {
    var diff = minWidth - value.length;
    if (diff > 0) {
      if (leftJustify || !zeroPad) {
        value = pad(value, minWidth, customPadChar, leftJustify);
      } else {
        value = value.slice(0, prefix.length) + pad('', diff, '0', true) + value.slice(prefix.length);
      }
    }
    return value;
  };

  // formatBaseX()
  var formatBaseX = function(value, base, prefix, leftJustify, minWidth, precision, zeroPad) {
    // Note: casts negative numbers to positive ones
    var number = value >>> 0;
    prefix = prefix && number && {
      '2': '0b',
      '8': '0',
      '16': '0x'
    }[base] || '';
    value = prefix + pad(number.toString(base), precision || 0, '0', false);
    return justify(value, prefix, leftJustify, minWidth, zeroPad);
  };

  // formatString()
  var formatString = function(value, leftJustify, minWidth, precision, zeroPad, customPadChar) {
    if (precision != null) {
      value = value.slice(0, precision);
    }
    return justify(value, '', leftJustify, minWidth, zeroPad, customPadChar);
  };

  // doFormat()
  var doFormat = function(substring, valueIndex, flags, minWidth, _, precision, type) {
    var number, prefix, method, textTransform, value;

    if (substring === '%%') {
      return '%';
    }

    // parse flags
    var leftJustify = false;
    var positivePrefix = '';
    var zeroPad = false;
    var prefixBaseX = false;
    var customPadChar = ' ';
    var flagsl = flags.length;
    for (var j = 0; flags && j < flagsl; j++) {
      switch (flags.charAt(j)) {
        case ' ':
          positivePrefix = ' ';
          break;
        case '+':
          positivePrefix = '+';
          break;
        case '-':
          leftJustify = true;
          break;
        case "'":
          customPadChar = flags.charAt(j + 1);
          break;
        case '0':
          zeroPad = true;
          customPadChar = '0';
          break;
        case '#':
          prefixBaseX = true;
          break;
      }
    }

    // parameters may be null, undefined, empty-string or real valued
    // we want to ignore null, undefined and empty-string values
    if (!minWidth) {
      minWidth = 0;
    } else if (minWidth === '*') {
      minWidth = +a[i++];
    } else if (minWidth.charAt(0) == '*') {
      minWidth = +a[minWidth.slice(1, -1)];
    } else {
      minWidth = +minWidth;
    }

    // Note: undocumented perl feature:
    if (minWidth < 0) {
      minWidth = -minWidth;
      leftJustify = true;
    }

    if (!isFinite(minWidth)) {
      throw new Error('sprintf: (minimum-)width must be finite');
    }

    if (!precision) {
      precision = 'fFeE'.indexOf(type) > -1 ? 6 : (type === 'd') ? 0 : undefined;
    } else if (precision === '*') {
      precision = +a[i++];
    } else if (precision.charAt(0) == '*') {
      precision = +a[precision.slice(1, -1)];
    } else {
      precision = +precision;
    }

    // grab value using valueIndex if required?
    value = valueIndex ? a[valueIndex.slice(0, -1)] : a[i++];

    switch (type) {
      case 's':
        return formatString(String(value), leftJustify, minWidth, precision, zeroPad, customPadChar);
      case 'c':
        return formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, zeroPad);
      case 'b':
        return formatBaseX(value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
      case 'o':
        return formatBaseX(value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
      case 'x':
        return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
      case 'X':
        return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad)
          .toUpperCase();
      case 'u':
        return formatBaseX(value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
      case 'i':
      case 'd':
        number = +value || 0;
        number = Math.round(number - number % 1); // Plain Math.round doesn't just truncate
        prefix = number < 0 ? '-' : positivePrefix;
        value = prefix + pad(String(Math.abs(number)), precision, '0', false);
        return justify(value, prefix, leftJustify, minWidth, zeroPad);
      case 'e':
      case 'E':
      case 'f': // Should handle locales (as per setlocale)
      case 'F':
      case 'g':
      case 'G':
        number = +value;
        prefix = number < 0 ? '-' : positivePrefix;
        method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(type.toLowerCase())];
        textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(type) % 2];
        value = prefix + Math.abs(number)[method](precision);
        return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]();
      default:
        return substring;
    }
  };

  return format.replace(regex, doFormat);
}

function number_format (number, decimals, decPoint, thousandsSep) {
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
  var n = !isFinite(+number) ? 0 : +number
  var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
  var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
  var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
  var s = ''
  var toFixedFix = function (n, prec) {
    var k = Math.pow(10, prec)
    return '' + (Math.round(n * k) / k)
      .toFixed(prec)
  }
  // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || ''
    s[1] += new Array(prec - s[1].length + 1).join('0')
  }
  return s.join(dec)
}