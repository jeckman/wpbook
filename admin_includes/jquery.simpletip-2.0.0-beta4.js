/**
 * jquery.simpletip 2.0.0-beta4. A simple tooltip plugin
 * 
 * Copyright (c) 2009 Craig Thompson
 * http://craigsworks.com
 *
 * Licensed under LGPLv3
 * http://www.opensource.org/licenses/lgpl-3.0.html
 *
 * Launch  : February 2009
 * Version : 2.0.0-beta4
 * Released: February 17, 2009 - 01:29am
 */
(function($)
{
   function Simpletip(root, contents, conf)
   {
      //Check configuration
      if(conf.target === null) conf.target = root;
      if(typeof conf.stem == 'string') conf.stem = { corner: conf.stem };
      if(typeof conf.stem !== false) conf.stem = jQuery.extend(true, { corner: false, size: 12, color: '#ccc' }, conf.stem);
      
      if(typeof conf.border == 'string') conf.border = { size: conf.border };
      conf.border = jQuery.extend(true, { size: 3, radius: 0, color: conf.stem.color }, conf.border);
      
      if(typeof conf.title == 'string') conf.title = { content: conf.title };
      
      //Define fundamental attributes
      var self = this;
      var showTimer, hideTimer;
      self.root = root;
      self.tooltip = self.title = self.content = self.stem = null;
      
      //Define coordinates of the stems (VML and Canvas)
      var max = conf.stem.size;
      var half = max / 2;
      var stems = {
         bottomRight:   [[0,0],        [max,max],  [max,0]],
         bottomLeft:    [[0,0],        [max,0],    [0,max]],
         topRight:      [[0,max],      [max,0],    [max,max]],
         topLeft:       [[0,0],        [0,max],    [max,max]],
         topMiddle:     [[0,max],      [half,0],   [max,max]],
         bottomMiddle:  [[0,0],        [max,0],    [half,max]],
         rightMiddle:   [[max,half],   [0,max],    [0,0]],
         leftMiddle:    [[max,0],      [max,max],  [0,half]]
      }
      stems.leftTop = stems.bottomRight; stems.rightTop = stems.bottomLeft;
      stems.leftBottom = stems.topRight; stems.rightBottom = stems.topLeft;
      delete max; delete half;
      
      jQuery.extend(self,
      {
         create: function()
         {
            //Create tooltip element
            self.tooltip = jQuery(document.createElement('div'))
               .addClass('simpletip')
               .addClass(conf.parentClass)
               .appendTo(document.body)
               .data("simpletip", self); 
            
            //Create content element
            self.content = jQuery(document.createElement('div'))
                  .addClass(conf.contentClass)
                  .html(contents)
                  .css('width', (conf.width || null) )
                  .appendTo(self.tooltip);
            
            //Create title element if enabled
            if(conf.title !== false) self.createTitle();
            
            //Create borders and stem and assign events
            self.assignEvents();
            self.createBorder();
            self.createStem();
            
            //Hide tooltip if hidden is true and focus
            self.focus();
            if(!conf.hidden) self.tooltip.show();
            else self.tooltip.hide();
         },
         
         createTitle: function()
         {
            //Create title element if enabled
            self.title = jQuery(document.createElement('div'))
               .addClass(conf.titleClass)
               .html(conf.title.content)
               .prependTo(self.content);
            
            //Create title button if enabled
            if(conf.title.button !== false)
            {
               var hideClass = conf.hideOn.element || 'close';
               var content = conf.title.button || '';
               
               jQuery(document.createElement('a'))
                  .addClass(hideClass)
                  .addClass(self.buttonClass)
                  .css('cursor', 'pointer')
                  .html(content)
                  .prependTo(self.title);
            }
         },
         
         createStem: function(corner)
         {
            if(conf.stem === false || conf.stem.corner === false) return;
            else if(!corner) corner = conf.stem.corner;
            
            //Remove previous stems from tooltip
            jQuery(self.tooltip).find('.'+conf.stemClass).remove();
            
            //Create stem element
            self.stem = jQuery(document.createElement('div'))
               .addClass(conf.stemClass)
               .attr('rel', corner)
               .css('position', 'absolute');
            
            //Use canvas element if supported
            if(document.createElement('canvas').getContext)
            {
               //Create canvas element
               var canvas = jQuery(document.createElement('canvas'))
                  .attr('width', conf.stem.size)
                  .attr('height', conf.stem.size)
                  .appendTo(self.stem);
               
               //Setup properties
               var context = canvas.get(0).getContext('2d');
               var coordinates = stems[corner];
               context.fillStyle = conf.stem.color;
               
               //Create stem
               context.beginPath();
               context.moveTo(coordinates[0][0], coordinates[0][1]);
               context.lineTo(coordinates[1][0], coordinates[1][1]);
               context.lineTo(coordinates[2][0], coordinates[2][1]);
               context.fill();
            }
            
            //Canvas not supported - Use VML (IE)
            else if(jQuery.browser.msie || document.namespaces)
            {
               //Create XML namespace and vml styles
               if(document.namespaces["v"] == null) 
                  document.namespaces.add("v", "urn:schemas-microsoft-com:vml");
               var stylesheet = document.createStyleSheet().owningElement;
               stylesheet.styleSheet.cssText = "v\\:*{behavior:url(#default#VML); display: inline-block }";
               
               //Create stem path using predefined stem coordinates
               var coordinates = stems[corner];
               var path = 'm' + coordinates[0][0] + ',' + coordinates[0][1];
               path += ' l' + coordinates[1][0] + ',' + coordinates[1][1];
               path += ' ' + coordinates[2][0] + ',' + coordinates[2][1];
               path += ' xe';
               
               //Create VML element
               jQuery(document.createElement('v:shape'))
                  .attr('fillcolor', conf.stem.color)
                  .attr('stroked', 'false')
                  .attr('coordsize', conf.stem.size + ',' + conf.stem.size)
                  .attr('path', path)
                  .css({ width: conf.stem.size, height: conf.stem.size, marginTop: -1 })
                  .appendTo(self.stem)
            }
            
            //Neither is supported, return
            else return;
            
            //Set adjustment variables
            var radiusAdjust = conf.border.radius;
            var sideAdjust = (conf.border.radius == 0) ? 0 : radiusAdjust;
            var pixelAdjust = (jQuery.browser.msie || document.namespaces) ? 1 : 0;
            
            //Adjust positions
            if(corner.search(/left|right/) !== -1)
            {
               if(corner.search(/Middle/) !== -1)
                  self.stem.css({ marginTop: Math.floor((self.tooltip.outerHeight() / 2) - (conf.stem.size / 2)) });
                  
               else if(corner.search(/Bottom/) !== -1)
               { 
                  self.stem.css({ marginTop: Math.floor(self.tooltip.outerHeight() - conf.stem.size) }); 
                  self.tooltip.css({ marginTop: radiusAdjust })
               }
               else if(corner.search(/Top/) !== -1)
               { 
                  self.stem.css({ marginTop: radiusAdjust }); 
                  self.tooltip.css({ marginTop: -radiusAdjust }) 
               }
                  
               if(corner.search(/left/) !== -1)
                  self.stem.css({ marginLeft: -conf.stem.size });
               else
                  self.stem.css({ marginLeft: self.tooltip.outerWidth() - 1 - pixelAdjust });
            }
            else if(corner.search(/top|bottom/) !== -1)
            {
               if(corner.search(/Middle/) !== -1)
                  self.stem.css({ marginLeft: Math.floor((self.tooltip.outerWidth() / 2) - (conf.stem.size / 2)) });
                  
               else if(corner.search(/Right/) !== -1)
               {
                  self.stem.css({ marginLeft: Math.floor(self.tooltip.outerWidth() - conf.stem.size - sideAdjust - pixelAdjust) }); 
                  self.tooltip.css({ marginLeft: sideAdjust }) 
               }
               else if(corner.search(/Left/) !== -1)
               {
                  self.stem.css({ marginLeft: radiusAdjust - pixelAdjust }); 
                  self.tooltip.css({ marginLeft: -radiusAdjust }) 
               }
                  
               if(corner.search(/top/) !== -1) self.stem.css({ marginTop: -conf.stem.size + 1 });
            }
            
            //Attach new stem to tooltip element
            if(corner.search(/left|top|rightMiddle/) !== -1)
               self.stem.prependTo(self.tooltip);
            else
               self.stem.appendTo(self.tooltip);
               
            //Adjust tooltip padding
            var paddingCorner = 'padding-' + corner.match(/left|right|top|bottom/)[0];
            self.tooltip.css(paddingCorner, conf.stem.size - 1);
         },
         
         createBorder: function()
         {
            size = conf.border.size || 0;
            radius = conf.border.radius;
            color = conf.border.color;
            
            //Reset border radius styles
            self.content.css('border-radius', '0px');
            self.content.css('-moz-border-radius', '0px');
            self.content.css('-webkit-border-radius', '0px');
            self.content.css('margin', 0);
            
            if(radius == 0)
               self.content.css({ border: size+'px solid '+color })
            else
            {
               //Define borders
               var borders = {
                  topLeft: [radius,radius], topRight: [0,radius],
                  bottomLeft: [radius,0], bottomRight: [0,0]
               }
               
               //Define shape container elements
               var shapes = {};
               for(var i in borders)
               {
                  shapes[i] = jQuery(document.createElement('div'))
                     .css({ height: radius, width: radius })
                     .css('overflow', 'hidden')
                     .css('float', (i.search(/Left/) !== -1) ? 'left' : 'right')
               }
               
               //Use canvas element if supported
               if(document.createElement('canvas').getContext)
               {
                  for(var i in borders)
                  {
                     var canvas = jQuery(document.createElement('canvas'))
                        .attr('height', radius)
                        .attr('width', radius)
                        .appendTo(shapes[i]);
                  
                     //Create corner
                     var context = canvas.get(0).getContext('2d');
                     context.fillStyle = color;
                     context.beginPath();
                     context.arc(borders[i][0], borders[i][1], radius, 0, Math.PI * 2, false);
                     context.fill();
                  }
               }
               
               //Canvas not supported - Use VML (IE)
               else if(jQuery.browser.msie || document.namespaces)
               {
                  //Create XML namespace and VML styles
                  if(document.namespaces["v"] == null) 
                     document.namespaces.add("v", "urn:schemas-microsoft-com:vml");
                  var stylesheet = document.createStyleSheet().owningElement;
                  stylesheet.styleSheet.cssText = "v\\:*{behavior:url(#default#VML); display: inline-block }";
                  
                  //Define borders
                  var borders = {
                     topLeft: [-1,-1], topRight: [-radius, -1],
                     bottomLeft: [-1, -radius], bottomRight: [-radius, -radius]
                  }
                  
                  for(var i in borders)
                  {
                     //Create VML arc
                     jQuery(document.createElement('v:roundrect'))
                        .attr('fill', 'true')
                        .attr('fillcolor', color)
                        .attr('stroked', 'false')
                        .attr('arcsize', radius)
                        .css({ 
                           width: radius * 2, height: radius * 2, 
                           marginTop: borders[i][1], marginLeft: borders[i][0]
                        })
                        .appendTo(shapes[i]);
                        
                     if(i.search(/Left/) !== -1) shapes[i].css({ marginRight: -3 })
                     else if(i.search(/Right/) !== -1) shapes[i].css({ marginLeft: -3 })
                  }
                  
                  //IE only adjustments
                  self.tooltip.css({ width: self.content.outerWidth() });
                  self.content.css({ width: 'auto' })
               }
               
               //Create between corners
               var betweenCorners = jQuery(document.createElement('div'))
                  .addClass('betweenCorners')
                  .css({ 
                     height: radius, 
                     overflow: 'hidden',
                     backgroundColor: color
                  })
               
               //Create containers
               var borderTop = jQuery(document.createElement('div'))
                  .addClass('borderTop')
                  .css({ height: radius, position: 'relative' })
                  .append(shapes['topLeft'])
                  .append(shapes['topRight'])
                  .append(betweenCorners)
                  .prependTo(self.tooltip);
                  
               var borderBottom = jQuery(document.createElement('div'))
                  .addClass('borderBottom')
                  .css({ height: radius })
                  .append(shapes['bottomLeft'])
                  .append(shapes['bottomRight'])
                  .append(betweenCorners.clone())
                  .appendTo(self.tooltip);
               
               //Setup container
               var sideWidth = Math.max(radius, (radius + (size - radius)) )
               var vertWidth = Math.max(size - radius, 0);
               self.content.css({
                  margin: 0,
                  border: '0px solid ' + color,
                  borderWidth: vertWidth + 'px ' + sideWidth + 'px'
               })
            }
         },
         
         assignEvents: function()
         {
            if(!conf.hook.mouse)
            {
               self.root.unbind(conf.showOn).bind(conf.showOn, function(event)
               { 
                  if(conf.showOn === conf.hideOn)
                  {
                     if(self.tooltip.css('display') !== 'none')
                     {
                        clearTimeout(showTimer); 
                        self.hide(event);
                        return;
                     }
                  }
                  
                  if(showTimer !== null) clearTimeout(showTimer);
                  
                  showTimer = setTimeout(function()
                  { 
                     self.show(event);
                  }, conf.delay)
                  
                  if(conf.hideAfter !== false)
                  {
                     var resetEvents = [
                        'click', 'dblclick', 'mousedown', 
                        'mouseup', 'mousemove', 'mouseout',
                        'mouseenter', 'mouseleave', 'mouseover'];
                  
                     function hideCallback()
                     {
                        clearTimeout(hideTimer);
                        hideTimer = setTimeout(function()
                        {
                           jQuery(resetEvents).each(function(){
                              self.root.unbind(this, hideCallback)
                              self.tooltip.unbind(this, hideCallback)
                           })
                           
                           self.hide();
                        }, conf.hideAfter);
                     }
                     
                     jQuery(resetEvents).each(function(){
                        self.root.bind(this, hideCallback)
                        self.tooltip.bind(this, hideCallback)
                     })
                  }
               });
            
               if(typeof conf.hideOn == 'string')
               {
                  if(conf.showOn !== conf.hideOn)
                     self.root.unbind(conf.hideOn).bind(conf.hideOn, function(){ clearTimeout(showTimer); self.hide(); });
                  
                  self.assignCloseEvents();
               }
               else
                  self.assignCloseEvents(conf.hideOn.element, conf.hideOn.event);
            }
            
            //Mouse hooking is enabled
            else 
            {
               self.root.mousemove(self.updatePos);
               self.root.bind(conf.showOn, self.show);
               self.root.mouseout(self.hide);
               
               self.assignCloseEvents();
            }
            
            //Assign focus events
            self.root.mouseover(self.focus);
            self.tooltip.mouseover(self.focus);
            
            //Retrieve ajax content if provided
            if(conf.ajax !== false && conf.ajax.url !== null)
            {
               var url = conf.ajax.url;
               var data = conf.ajax.data;
               var method = conf.ajax.method || 'get';
               
               self.load(url, data, method);
            }
         },
         
         assignCloseEvents: function(closeClass, event)
         {
            event = event || 'mousedown';
            closeClass = closeClass || '.close';
            
            self.content.find(closeClass).bind(event, function(){ self.hide(); return false; });
         },
         
         set: function(name, value)
         {
            return conf[name] = value;
         },
      
         getVersion: function()
         {
            return [2, 0, 0, 'beta4'];
         },
         
         getTooltip: function()
         {
            return self.tooltip;
         },
         
         getContent: function()
         {
            return self.content;
         },
         
         getPos: function()
         {
            return self.tooltip.offset();
         },
         
         show: function(event)
         {
            conf.beforeShow.call(self);
            
            if(conf.hideOthers) self.hideOthers()
            self.updatePos(event);
            
            if(typeof conf.showEffect == 'function')
               conf.showEffect.call(self.tooltip, conf.showTime);
            else
            {
               switch(conf.showEffect)
               {
                  case 'fade': 
                     self.tooltip.fadeIn(conf.showTime); break;
                  case 'slide': 
                     self.tooltip.slideDown(conf.showTime, function(){ self.updatePos(event) }); break;
                  default:
                  case 'none':
                     self.tooltip.show(); break;
               }
            }
            
            self.tooltip.addClass(conf.activeClass);
            
            conf.onShow.call(self);
            
            return self;
         },
         
         hide: function()
         {
            conf.beforeHide.call(self);
            
            if(typeof conf.hideEffect == 'function')
               conf.hideEffect.call(self.tooltip, conf.hideTime);
            else
            {
               switch(conf.hideEffect)
               {
                  case 'fade': 
                     self.tooltip.fadeOut(conf.hideTime); break;
                  case 'slide': 
                     self.tooltip.slideUp(conf.hideTime); break;
                  default:
                  case 'none':
                     self.tooltip.hide(); break;
               }
            }
            
            self.tooltip.removeClass(conf.activeClass);
            
            conf.onHide.call(self);
            
            return self;
         },
         
         hideOthers: function()
         {
            jQuery('div.simpletip').not(self.tooltip).each(function()
            {
               jQuery(this).simpletip().hide();
            });
         },
         
         focus: function()
         {
            jQuery('div.simpletip').not(self.tooltip).each(function()
            {
               jQuery(this).css('z-index', 6000);
            });
            
            self.tooltip.css('z-index', 6001);
         },
         
         update: function(newContents)
         {
            if(conf.title !== false) 
               self.content.find('.'+conf.titleClass).eq(0).after(newContents);
            else
               self.content.html(newContents);
            
            self.assignEvents();
            
            return self;
         },
         
         load: function(url, data, method)
         {
            if(method)
            {
               if(method.search(/post/i) !== -1 && data.length < 0) 
                  data = null;
               else if(method.search(/get/i) !== -1)
                  data = null;
            }
            
            conf.beforeContentLoad.call(self);
            
            self.content.load(url, data, function()
            { 
               conf.onContentLoad.call(self); 
               if(conf.title !== false) self.createTitle();
               self.assignCloseEvents(); 
               self.updatePos(); 
            });
            
            return self;
         },
         
         viewportCheck: function(posX, posY)
         {
            var newX = posX + self.tooltip.outerWidth();
            var newY = posY + self.tooltip.outerHeight();
            
            var windowWidth = jQuery(window).width() + jQuery(window).scrollLeft();
            var windowHeight = jQuery(window).height() + jQuery(window).scrollTop();
            
            return { leftMin: (posX < 0), 
                     leftMax: (newX >= windowWidth), 
                     topMin: (posY < jQuery(window).scrollTop()), 
                     topMax: (newY >= windowHeight) };
         },
         
         viewportAdjust: function(posX, posY, event)
         {
            var overflow = self.viewportCheck(posX, posY);
            var corner = conf.stem.corner || conf.hook.self.tooltip;
            
            if(overflow.leftMin || overflow.leftMax)
            {
               if(overflow.leftMin)
                  posX = (conf.hook.mouse) ? event.pageX : conf.target.offset().left + conf.target.outerWidth();
               else if(overflow.leftMax)
               {
                  if(corner.search(/(top|bottom)Middle/) !== -1)
                     posX = posX - (self.tooltip.outerWidth() / 2) - (conf.offset[0] * 2);
                  else
                     posX = posX - conf.target.outerWidth() - self.tooltip.outerWidth() - (conf.offset[0] * 2);
               }
               
               if(conf.stem !== false && conf.stem.corner !== false)
               {
                  if(corner.search(/(top|bottom)Middle/) !== -1)
                  {
                     if(overflow.leftMin)
                        corner = corner.replace('Middle', 'Left');
                     else if(overflow.leftMax)
                        corner = corner.replace('Middle', 'Right');
                        
                  }
                  else if(corner.search(/right/) !== -1) corner = corner.replace('right', 'left');
                  else if(corner.search(/Right/) !== -1) corner = corner.replace('Right', 'Left');
                  else if(corner.search(/left/) !== -1) corner = corner.replace('left', 'right');
                  else if(corner.search(/Left/) !== -1) corner = corner.replace('Left', 'Right');
               }
            }
            
            if(overflow.topMin || overflow.topMax)
            {
               if(overflow.topMin)
                  posY = (conf.hook.mouse) ? event.pageY : conf.target.offset().top + conf.target.outerHeight();
               else if(overflow.topMax)
               {
                  if(corner.search(/(left|right)Middle/) !== -1)
                     posY = posY - (conf.target.outerHeight() / 2) - (self.tooltip.outerHeight() / 2) - (conf.offset[1] * 2);
                  else
                     posY = posY - conf.target.outerHeight() - self.tooltip.outerHeight() - (conf.offset[1] * 2);
               }   
               
               if(conf.stem !== false && conf.stem.corner !== false)
               {
                  if(corner.search(/(left|right)Middle/) !== -1)
                  {
                     if(overflow.topMin) 
                        corner = corner.replace('Middle', 'Top');
                     else if(overflow.topMax)
                        corner = corner.replace('Middle', 'Bottom');
                  }
                  else if(corner.search(/top/) !== -1) corner = corner.replace('top', 'bottom');
                  else if(corner.search(/Top/) !== -1) corner = corner.replace('Top', 'Bottom');
                  else if(corner.search(/bottom/) !== -1) corner = corner.replace('bottom', 'top');
                  else if(corner.search(/Bottom/) !== -1) corner = corner.replace('Bottom', 'Top');
               }
            }
            
            if(conf.stem !== false && conf.stem.corner !== false)
               if(corner != self.stem.attr('rel')) self.createStem(corner);
            
            return { left: posX, top: posY };
         },
         
         cornerPos: function(elem, corner)
         {
            var elemPos = elem.offset();
            var posX = elemPos.left;
            var posY = elemPos.top;
            
            if(corner.search(/bottom/i) !== -1) posY += elem.outerHeight();
            if(corner.search(/right/i) !== -1) posX += elem.outerWidth();
            
            if(corner.search(/(left|right)Middle/) !== -1) posY += elem.outerHeight() / 2;
            else if(corner.search(/(top|bottom)Middle/) !== -1) posX += elem.outerWidth() / 2;
            
            return { left: posX, top: posY };
         },
         
         updatePos: function(event)
         {
            if(!conf.hook.mouse)
            {
               var rootPos = self.cornerPos(conf.target, conf.hook.target);
               
               var posX = rootPos.left;
               var posY = rootPos.top;
            }
            else
            {
               var posX = event.pageX;
               var posY = event.pageY;
            }
            
            if(conf.hook.tooltip.search(/bottom/i) !== -1) posY -= self.tooltip.outerHeight();
            if(conf.hook.tooltip.search(/right/i) !== -1) posX -= self.tooltip.outerWidth();
               
            if(conf.hook.tooltip.search(/(left|right)Middle/) !== -1) posY -= self.tooltip.outerHeight() / 2;
            else if(conf.hook.tooltip.search(/(top|bottom)Middle/) !== -1) posX -= self.tooltip.outerWidth() / 2;
            
            posX += conf.offset[0];
            posY += conf.offset[1];
            
            if(conf.viewport) 
            {
               var newPos = self.viewportAdjust(posX, posY, event);
               posX = newPos.left;
               posY = newPos.top;
            }
            
            if(conf.hook.mouse)
            {
               var adjust = (conf.hook.tooltip.search(/top/) !== -1) ? 5 : -5;
               posX += adjust;
               posY += adjust;
            }
            
            self.tooltip.css({ left: posX, top: posY });
            
            resizing = false;
            return self;
         }
      });
      
      self.create(); //Create tooltip
   };
   
   jQuery.fn.simpletip = function(contents, userConf)
   { 
      // Check if a simpletip is already present
      var api = jQuery(this).eq(typeof conf == 'number' ? conf : 0).data("simpletip");
      if(api) return api;
      
      // Check content is provided
      if(contents === null) contents = '';
      
      // Default configuration
      var defaultConf = {
         // Positioning
         target: null,
         title: false,
         ajax: false,
         hook: { tooltip: 'topLeft', target: 'bottomRight', mouse: false },
         stem: { corner: false, color: '#ccc', size: 12 },
         offset: [0, 0],
         viewport: false,
         hidden: true,
         
         // Show
         showOn: 'mousemove',
         showEffect: 'fade',
         showTime: 150,
         
         // Hiding
         delay: 140,
         hideAfter: false,
         hideOthers: false,
         hideOn: 'mouseout',
         hideEffect: 'fade',
         hideTime: 150,
         
         // Styles and classes
         parentClass: 'tooltip',
         stemClass: 'stem',
         titleClass: 'title',
         buttonClass: 'button',
         contentClass: 'content',
         activeClass: 'active',
         width: false,
         border: 3,
         
         // Callbacks
         beforeShow: function(){},
         onShow: function(){},
         beforeHide: function(){},
         onHide: function(){},
         beforeContentLoad: function(){},
         onContentLoad: function(){}
      }
      jQuery.extend(true, defaultConf, userConf);
      
      this.each(function()
      {
         var el = new Simpletip(jQuery(this), contents, defaultConf);
         jQuery(this).data("simpletip", el);  
      });
      
      return this; 
   };
})();