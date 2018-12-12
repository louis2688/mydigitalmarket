(function(wp) {
  'use strict';

  // Colors.
  var colorSettings = [
    'text_color',
    'link_color',
    'link_hover',
  ];

  // Generate the CSS for the current Color Scheme.
  var bindingUpdateCSS = function () {
    var color = [];

    _.each( colorSettings, function( setting ) {
      var name = 'circle_color['+setting+']';
      color[setting] = wp.customize(name)();
    });

    var css = wp.template('circle-color-scheme')(color);
    wp.customize.previewer.send('update-color-scheme-css', css);
  }

  // Update the CSS whenever a color setting is changed.
  _.each(colorSettings, function (setting) {
    var name = 'circle_color['+setting+']';
    wp.customize(name, function(value) { value.bind(bindingUpdateCSS); });
  });

})(wp);
