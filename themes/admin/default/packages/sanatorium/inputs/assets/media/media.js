/**
 * Created by Mac on 16/04/16.
 */

/**
 * Media manager object allows user to
 * show media manager, provides ability
 * to select, upload, delete, edit etc.
 * all kinds of media files.
 * 
 * @type {{}}
 */
var MediaManager = {

  $loadable: null,

  cacheSelectors: function() {

    this.$loadable = $('[data-media-load]');

    this.$managers = $('.media-manager')

  },

  init: function() {

    this.cacheSelectors();

    this.addGlobalListeners();

  },

  addGlobalListeners: function() {

    var self = this;

    this.$managers.on('show.bs.modal', function (e) {

      self.loadLists();

    });

  },

  loadLists: function() {

    var self = this;

    this.$loadable.each(function(){

      var url = $(this).data('[media-load]');
      console.log(url);
      $.get({
        url: url
      }).success(function(data){
        console.log(data);
      });

    });

  },


};

MediaManager.init();