(function($) {

  /**
   * Configure a collection object based on a DOM element like a button:
   *
   * @param element
   * @constructor
   */
  var Collection = function(element) {
    this.fieldsetContainerClass = element.data('fieldset-container-class') ? element.data('fieldset-container-class') :'form > fieldset';
    this.fieldsetClass = element.data('fieldset-class') ? element.data('fieldset-class') : 'fieldset';
    this.indexKey = element.data('index-key') ? element.data('index-key') : '__index__';

    /**
     * Load the fieldset container class from configuration
     *
     * @returns {*|jQuery|HTMLElement}
     */
    this.getContainer = function() {
      return $(this.fieldsetContainerClass);
    };

    /**
     * Load all collection fieldsets
     * @returns {*|jQuery|HTMLElement}
     */
    this.getFieldsets = function() {
      return $(this.fieldsetContainerClass + ' > ' + this.fieldsetClass);
    };

    /**
     * create a template object
     * @returns {CollectionTemplate}
     */
    this.getTemplate = function() {
      var templateString = $(this.fieldsetContainerClass + ' > span').data('template');
      return new CollectionTemplate(templateString, this.indexKey);
    };

    /**
     * Create a new collection based on the template
     */
    this.createFromTemplate = function() {
      var fieldsets = this.getFieldsets();
      var template = this.getTemplate().parse(fieldsets.length);
      this.getContainer().append(template);
    };

    /**
     * Remove a collection item by its index
     * @param index
     */
    this.removeByIndex = function(index) {
      var fieldsets = this.getFieldsets();
      if (index >= fieldsets.length || index < 0) {
        return;
      }

      fieldsets.eq(index).remove();
    };

  };

  /**
   *
   * @param templateString
   * @param indexKey
   * @constructor
   */
  var CollectionTemplate = function(templateString, indexKey)
  {

    this.indexKey = indexKey;
    this.template = templateString;

    /**
     * Parse a template with a specific index
     *
     * @param index
     * @returns {string}
     */
    this.parse = function(index){
      var indexRegex = new RegExp(this.indexKey, 'g');
      return this.template.replace(indexRegex, index);
    };

  };

  /**
   * Add events to DOM
   */
  $(function()  {

    /**
     * This method provides an easy way to configure an add collection item button.
     * Some configuration params:
     *
     * data-fieldset-container-class="form > fieldset > fieldset"
     * data-fieldset-class="fieldset.className"
     * data-index-key = "__index__"
     *
     */
    $('.form-add-collection-item').click(function(e) {
      e.preventDefault();
      var element = $(this);
      var collection = new Collection(element);

      collection.createFromTemplate();
    });

    /**
     * This method provides an easy way to configure a remove collection item button.
     * Some configuration params:
     *
     * data-fieldset-container-class="form > fieldset > fieldset"
     * data-fieldset-class="fieldset.className"
     * data-index-key = "__index__"
     * data-current-index="0"
     */
    $('.form-remove-collection-item').click(function(e) {
      e.preventDefault();
      var element = $(this);
      var collection = new Collection(element);
      var currentIndex = (typeof element.data('current-index') != 'undefined') ? element.data('current-index') : -1;

      collection.removeByIndex(currentIndex);
    });

  });

})(jQuery);
