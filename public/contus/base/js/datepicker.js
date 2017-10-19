var datapicker = angular.module('mara.datapicker',['ui.bootstrap']);

datapicker.controller('DatepickerController', function () {
  this.today = function() {
    this.dt = new Date();
  };

  this.popup = {
    open : false
  };

  // Disable weekend selection
  this.disabled = function(date, mode) {
    return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
  };

  this.toggleMin = function() {
    this.minDate = this.minDate ? null : new Date();
  };

  this.toggleMin();
  this.maxDate = new Date(2020, 5, 22);

  this.open = function() {
    this.popup.opened = true;
  };

  this.open2 = function() {
    this.popup2.opened = true;
  };

  this.setDate = function(year, month, day) {
    this.dt = new Date(year, month, day);
  };

  this.dateOptions = {
    formatYear: 'yy',
    startingDay: 1
  };

  this.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd-MM-yyyy', 'shortDate'];
  this.format = this.formats[2];
  this.altInputFormats = ['M!/d!/yyyy'];

  this.popup1 = {
    opened: false
  };

  this.popup2 = {
    opened: false
  };

  var tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  var afterTomorrow = new Date();
  afterTomorrow.setDate(tomorrow.getDate() + 1);
  this.events =
    [
      {
        date: tomorrow,
        status: 'full'
      },
      {
        date: afterTomorrow,
        status: 'partially'
      }
    ];

  this.getDayClass = function(date, mode) {
    if (mode === 'day') {
      var dayToCheck = new Date(date).setHours(0,0,0,0);

      for (var i = 0; i < this.events.length; i++) {
        var currentDay = new Date(this.events[i].date).setHours(0,0,0,0);

        if (dayToCheck === currentDay) {
          return this.events[i].status;
        }
      }
    }

    return '';
  };
});
