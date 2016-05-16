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

  $loadable  : null,
  currentData: null,
  sort       : 'created_at',
  filters    : {
    images   : ['image/jpeg', 'image/png', 'image/gif'],
    documents: ['text/plain', 'application/pdf'],
    audio    : ['audio/ogg'],
    video    : ['video/mp4', 'video/ogg']
  },
  mode       : 'single',
  $manager   : null,
  $input     : null,

  cacheSelectors: function () {

    this.$loadable = this.$manager.find('[data-media-load]');

    this.$sorters = this.$manager.find('[data-media-sort]');

    this.$filters = this.$manager.find('[data-media-filter]');

    this.$sidebar = this.$manager.find('.media-manager-sidebar');

    this.mode = ( typeof this.$manager.data('mode') !== 'undefined' ? this.$manager.data('mode') : this.mode );

    this.input_name = this.$manager.data('input-name');

    this.form_group = this.$manager.data('form-group');

    this.$input = $('[name="' + this.$manager.data('input-name') + '"]');
  },

  init: function ($manager) {

    this.$manager = $manager;

    this.cacheSelectors();

    this.addGlobalListeners();

  },

  addGlobalListeners: function () {

    var self = this;

    // On modal display
    this.$manager.on('show.bs.modal', function (event) {

      self.loadLists();

    });

    this.listenSort();

    this.listenFilter();

  },

  listenFilter: function () {

    var self = this;

    this.$filters.change(function (event) {

      self.beforeLoad();

      var value = $(this).val();

      if (typeof self.filters[value] == 'undefined')
        return false;

      // @todo - make dynamic
      var key = 'mime';

      // On filter
      self.filterData(key, self.filters[value]);

    });

  },

  /**
   * @example filterData('mime', ['image/jpg, image/png'], this.showContents())
   * @param key
   * @param values
   */
  filterData: function (key, values) {

    var self = this;

    if (typeof self.currentData.media === 'undefined')
      return true;

    this.currentData.media = _.filter(self.currentData.all, function (element) {
      return values.indexOf(element[key]) !== -1;
    });

    this.showContents();

  },

  listenSort: function () {

    var self = this;

    this.$sorters.change(function (event) {

      self.beforeLoad();

      var value = $(this).val();

      // On sort change
      self.sortData(value);

    });

  },

  sortData: function (sortby) {

    var self = this;

    if (typeof self.currentData.media === 'undefined')
      return true;

    this.currentData.media = _.sortBy(self.currentData.media, function (item) {
      return item[sortby];
    });

    this.showContents();

  },

  loadLists: function () {

    var self = this;

    this.$loadable.each(function () {

      self.$el = $(this);

      var url = self.$el.data('media-load');

      $.ajax({
        type: 'GET',
        url : url
      }).success(function (data) {

        self.currentData = {
          media: data,
          count: data.length,
          all  : data,
        };

        self.showContents();

      });

    });

  },

  showContents: function () {

    var template = $('#media-preview-template').html(),
        html     = _.template(template)(this.currentData);

    this.$el.html(html);

    this.afterLoad();

  },

  beforeLoad: function () {

    this.$el.fadeOut(250);

  },

  afterLoad: function () {

    this.$el.fadeIn(250);

    this.activateThumbnails();

  },

  activateThumbnails: function () {

    var self = this;

    this.$el.find('.media-manager-preview:not(.activated)').click(function (event) {

      self.selectMedia($(this));

    }).addClass('activated');

  },

  selectMedia: function ($media) {

    var self = this;

    if (this.mode == 'single') {

      this.deselectAll();

      this.$input.val($media.data('id'));

    } else {

      $(self.form_group).find('.media-manager-input').remove();

      this.$el.find('.media-manager-preview.selected').each(function(){

        var $mediaPreview = $(this);

        $('<input type="hidden" class="media-manager-input">').attr({
          name: self.input_name + '[]',
          value: $mediaPreview.data('id'),
          type: 'hidden'
        }).appendTo(self.form_group);

      });

    }

    this.showSidebar($media.data());

    $media.toggleClass('selected');

  },

  deselectAll: function () {

    this.$el.find('.media-manager-preview.selected').removeClass('selected');

  },

  showSidebar: function (data) {

    console.log(data);

    var template = $('#media-sidebar-template').html(),
        html     = _.template(template)(data);

    this.$sidebar.html(html);

    // Init "Copy to clipboard" on [data-clipboard-target] element
    new Clipboard('[data-clipboard-target]');

  },


};

$('.media-manager').each(function () {

  MediaManager.init($(this));

});

// url http://stackoverflow.com/questions/10420352/converting-file-size-in-bytes-to-human-readable
function humanFileSize(bytes, si) {
  var thresh = si ? 1000 : 1024;
  if (Math.abs(bytes) < thresh) {
    return bytes + ' B';
  }
  var units = si
      ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
      : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
  var u     = -1;
  do {
    bytes /= thresh;
    ++u;
  } while (Math.abs(bytes) >= thresh && u < units.length - 1);
  return bytes.toFixed(1) + ' ' + units[u];
}