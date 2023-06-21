<?php
namespace NITSAN\NsTimeline\Backend;

class ItemsProcFunc
{

      /**
      * Modifies the Styles list options.
      *
      * @param array &$config configuration array
      */
      public function getNormalStylesList(array &$config): void
      {
            // change this to dynamically populate the list!
            $config['items'] = [
                  ['Style 01', 'style-1'],
                  ['Style 02', 'style-2'],
            ];
      }

    /**
    * Modifies the icon list options.
    *
    * @param array &$config configuration array
    */
    public function getIconsList(array &$config): void
    {
        // change this to dynamically populate the list!
        $config['items'] = [
                  // label, value
                  ['None', ''],
                  ['Adjust', 'fa-adjust'],
                  ['Align-center', 'fa-align-center'],
                  ['Align-justify', 'fa-align-justify'],
                  ['Align-left', 'fa-align-left'],
                  ['Align-right', 'fa-align-right'],
                  ['Ambulance', 'fa-ambulance'],
                  ['Anchor', 'fa-anchor'],
                  ['Angle-double-down', 'fa-angle-double-down'],
                  ['Angle-double-left', 'fa-angle-double-left'],
                  ['Angle-double-right', 'fa-angle-double-right'],
                  ['Angle-double-up', 'fa-angle-double-up'],
                  ['Angle-down', 'fa-angle-down'],
                  ['Angle-left', 'fa-angle-left'],
                  ['Angle-right', 'fa-angle-right'],
                  ['Angle-up', 'fa-angle-up'],
                  ['Archive', 'fa-archive'],
                  ['Arrow-circle-down', 'fa-arrow-circle-down'],
                  ['Arrow-circle-left', 'fa-arrow-circle-left'],
                  ['Arrow-circle-right', 'fa-arrow-circle-right'],
                  ['Arrow-circle-up', 'fa-arrow-circle-up'],
                  ['Arrow-down', 'fa-arrow-down'],
                  ['Arrow-left', 'fa-arrow-left'],
                  ['Arrow-right', 'fa-arrow-right'],
                  ['Arrow-up', 'fa-arrow-up'],
                  ['Arrows-alt', 'fa-arrows-alt'],
                  ['Asterisk', 'fa-asterisk'],
                  ['At', 'fa-at'],
                  ['Backward', 'fa-backward'],
                  ['Bacon', 'fa-bacon'],
                  ['Balance-scale', 'fa-balance-scale'],
                  ['Ban', 'fa-ban'],
                  ['Band-aid', 'fa-band-aid'],
                  ['Barcode', 'fa-barcode'],
                  ['Bars', 'fa-bars'],
                  ['Battery-empty', 'fa-battery-empty'],
                  ['Battery-full', 'fa-battery-full'],
                  ['Battery-half', 'fa-battery-half'],
                  ['Battery-quarter', 'fa-battery-quarter'],
                  ['Battery-three-quarters', 'fa-battery-three-quarters'],
                  ['Bed', 'fa-bed'],
                  ['Beer', 'fa-beer'],
                  ['Bell', 'fa-bell-o'],
                  ['Bell-slash', 'fa-bell-slash'],
                  ['Bicycle', 'fa-bicycle'],
                  ['Binoculars', 'fa-binoculars'],
                  ['Bold', 'fa-bold'],
                  ['Bolt', 'fa-bolt'],
                  ['Bomb', 'fa-bomb'],
                  ['Book', 'fa-book'],
                  ['Bookmark', 'fa-bookmark'],
                  ['Briefcase', 'fa-briefcase'],
                  ['Brush', 'fa-brush'],
                  ['Bug', 'fa-bug'],
                  ['Building', 'fa-building'],
                  ['Bullhorn', 'fa-bullhorn'],
                  ['Bullseye', 'fa-bullseye'],
                  ['Bus', 'fa-bus'],
                  ['Calculator', 'fa-calculator'],
                  ['Calendar', 'fa-calendar'],
                  ['Calendar-alt', 'fa-calendar-alt'],
                  ['Calendar-check', 'fa-calendar-check'],
                  ['Calendar-day', 'fa-calendar-day'],
                  ['Calendar-minus', 'fa-calendar-minus'],
                  ['Calendar-plus', 'fa-calendar-plus'],
                  ['Calendar-times', 'fa-calendar-times'],
                  ['Calendar-week', 'fa-calendar-week'],
                  ['Camera', 'fa-camera'],
                  ['Camera-retro', 'fa-camera-retro'],
                  ['Car', 'fa-car'],
                  ['Caret-down', 'fa-caret-down'],
                  ['Caret-left', 'fa-caret-left'],
                  ['Caret-right', 'fa-caret-right'],
                  ['Caret-square-down', 'fa-caret-square-down'],
                  ['Caret-square-left', 'fa-caret-square-left'],
                  ['Caret-square-right', 'fa-caret-square-right'],
                  ['Caret-square-up', 'fa-caret-square-up'],
                  ['Caret-up', 'fa-caret-up'],
                  ['Cart-arrow-down', 'fa-cart-arrow-down'],
                  ['Cart-plus', 'fa-cart-plus'],
                  ['Certificate', 'fa-certificate'],
                  ['Chart-area', 'fa-chart-area'],
                  ['Chart-bar', 'fa-chart-bar'],
                  ['Chart-line', 'fa-chart-line'],
                  ['Chart-pie', 'fa-chart-pie'],
                  ['Check', 'fa-check'],
                  ['Check-circle', 'fa-check-circle'],
                  ['Check-double', 'fa-check-double'],
                  ['Check-square', 'fa-check-square'],
                  ['Chevron-circle-down', 'fa-chevron-circle-down'],
                  ['Chevron-circle-left', 'fa-chevron-circle-left'],
                  ['Chevron-circle-right', 'fa-chevron-circle-right'],
                  ['Chevron-circle-up', 'fa-chevron-circle-up'],
                  ['Chevron-down', 'fa-chevron-down'],
                  ['Chevron-left', 'fa-chevron-left'],
                  ['Chevron-right', 'fa-chevron-right'],
                  ['Chevron-up', 'fa-chevron-up'],
                  ['Child', 'fa-child'],
                  ['Circle', 'fa-circle'],
                  ['Circle-notch', 'fa-circle-notch'],
                  ['Clock', 'fa-clock-o'],
                  ['Clone', 'fa-clone'],
                  ['Cloud', 'fa-cloud'],
                  ['Cloud-download', 'fa-cloud-download'],
                  ['Code', 'fa-code'],
                  ['Coffee', 'fa-coffee'],
                  ['Cog', 'fa-cog'],
                  ['Cogs', 'fa-cogs'],
                  ['Columns', 'fa-columns'],
                  ['Comment', 'fa-comment'],
                  ['Comments', 'fa-comments'],
                  ['Compass', 'fa-compass'],
                  ['Compress', 'fa-compress'],
                  ['Copy', 'fa-copy'],
                  ['Copyright', 'fa-copyright'],
                  ['Credit-card', 'fa-credit-card'],
                  ['Crop', 'fa-crop'],
                  ['Crosshairs', 'fa-crosshairs'],
                  ['Cube', 'fa-cube'],
                  ['Cubes', 'fa-cubes'],
                  ['Cut', 'fa-cut'],
                  ['Database', 'fa-database'],
                  ['Desktop', 'fa-desktop'],
                  ['Dollar-sign', 'fa-dollar-sign'],
                  ['Dot-circle', 'fa-dot-circle'],
                  ['Download', 'fa-download'],
                  ['Edit', 'fa-edit'],
                  ['Eject', 'fa-eject'],
                  ['Ellipsis-h', 'fa-ellipsis-h'],
                  ['Ellipsis-v', 'fa-ellipsis-v'],
                  ['Envelope', 'fa-envelope'],
                  ['Envelope-square', 'fa-envelope-square'],
                  ['Equals', 'fa-equals'],
                  ['Eraser', 'fa-eraser'],
                  ['Euro-sign', 'fa-euro-sign'],
                  ['Exclamation', 'fa-exclamation'],
                  ['Exclamation-circle', 'fa-exclamation-circle'],
                  ['Exclamation-triangle', 'fa-exclamation-triangle'],
                  ['Expand', 'fa-expand'],
                  ['Eye', 'fa-eye'],
                  ['Eye-dropper', 'fa-eye-dropper'],
                  ['Eye-slash', 'fa-eye-slash'],
                  ['Facebook', 'fa-facebook-f'],
                  ['Fast-backward', 'fa--backward'],
                  ['Fast-forward', 'fa--forward'],
                  ['Fax', 'fa-fax'],
                  ['Feather', 'fa-feather'],
                  ['Feather-alt', 'fa-feather-alt'],
                  ['Female', 'fa-female'],
                  ['Fighter-jet', 'fa-fighter-jet'],
                  ['File', 'fa-file'],
                  ['Film', 'fa-film'],
                  ['Filter', 'fa-filter'],
                  ['Fire', 'fa-fire'],
                  ['Flag', 'fa-flag'],
                  ['Flag-checkered', 'fa-flag-checkered'],
                  ['Flask', 'fa-flask'],
                  ['Folder', 'fa-folder'],
                  ['Font', 'fa-font'],
                  ['Forward', 'fa-forward'],
                  ['Genderless', 'fa-genderless'],
                  ['Ghost', 'fa-ghost'],
                  ['Gift', 'fa-gift'],
                  ['Gifts', 'fa-gifts'],
                  ['Globe', 'fa-globe'],
                  ['Greater-than', 'fa-greater-than'],
                  ['Greater-than-equal', 'fa-greater-than-equal'],
                  ['H-square', 'fa-h-square'],
                  ['Hashtag', 'fa-hashtag'],
                  ['Heading', 'fa-heading'],
                  ['Headphones', 'fa-headphones'],
                  ['Heart', 'fa-heart'],
                  ['Heartbeat', 'fa-heartbeat'],
                  ['History', 'fa-history'],
                  ['Home', 'fa-home'],
                  ['Hotel', 'fa-hotel'],
                  ['I-cursor', 'fa-i-cursor'],
                  ['Id-badge', 'fa-id-badge'],
                  ['Id-card', 'fa-id-card'],
                  ['Image', 'fa-image'],
                  ['Inbox', 'fa-inbox'],
                  ['Indent', 'fa-indent'],
                  ['Industry', 'fa-industry'],
                  ['Infinity', 'fa-infinity'],
                  ['Info', 'fa-info'],
                  ['Info-circle', 'fa-info-circle'],
                  ['Italic', 'fa-italic'],
                  ['Key', 'fa-key'],
                  ['Keyboard', 'fa-keyboard'],
                  ['Landmark', 'fa-landmark'],
                  ['Language', 'fa-language'],
                  ['Laptop', 'fa-laptop'],
                  ['Leaf', 'fa-leaf'],
                  ['Lemon', 'fa-lemon'],
                  ['Life-ring', 'fa-life-ring'],
                  ['Lightbulb', 'fa-lightbulb-o'],
                  ['Link', 'fa-link'],
                  ['List', 'fa-list'],
                  ['List-alt', 'fa-list-alt'],
                  ['List-ol', 'fa-list-ol'],
                  ['List-ul', 'fa-list-ul'],
                  ['Location-arrow', 'fa-location-arrow'],
                  ['Lock', 'fa-lock'],
                  ['Lock-open', 'fa-lock-open'],
                  ['Linkedin', 'fa-linkedin-in'],
                  ['Magic', 'fa-magic'],
                  ['Magnet', 'fa-magnet'],
                  ['Male', 'fa-male'],
                  ['Map', 'fa-map'],
                  ['Mars', 'fa-mars'],
                  ['Mars-double', 'fa-mars-double'],
                  ['Mars-stroke', 'fa-mars-stroke'],
                  ['Mars-stroke-h', 'fa-mars-stroke-h'],
                  ['Mars-stroke-v', 'fa-mars-stroke-v'],
                  ['Mask', 'fa-mask'],
                  ['Medal', 'fa-medal'],
                  ['Medkit', 'fa-medkit'],
                  ['Minus', 'fa-minus'],
                  ['Minus-circle', 'fa-minus-circle'],
                  ['Minus-square', 'fa-minus-square'],
                  ['Mobile', 'fa-mobile'],
                  ['Mobile-alt', 'fa-mobile-alt'],
                  ['Motorcycle', 'fa-motorcycle'],
                  ['Mouse-pointer', 'fa-mouse-pointer'],
                  ['Music', 'fa-music'],
                  ['Newspaper', 'fa-newspaper'],
                  ['Om', 'fa-om'],
                  ['Pager', 'fa-pager'],
                  ['Paint-brush', 'fa-paint-brush'],
                  ['Paper-plane', 'fa-paper-plane'],
                  ['Paperclip', 'fa-paperclip'],
                  ['Paragraph', 'fa-paragraph'],
                  ['Paste', 'fa-paste'],
                  ['Pause', 'fa-pause'],
                  ['Paw', 'fa-paw'],
                  ['Phone', 'fa-phone'],
                  ['Plane', 'fa-plane'],
                  ['Play', 'fa-play'],
                  ['Play-circle', 'fa-play-circle'],
                  ['Plug', 'fa-plug'],
                  ['Plus', 'fa-plus'],
                  ['Plus-circle', 'fa-plus-circle'],
                  ['Plus-square', 'fa-plus-square'],
                  ['Podcast', 'fa-podcast'],
                  ['Power-off', 'fa-power-off'],
                  ['Print', 'fa-print'],
                  ['Puzzle-piece', 'fa-puzzle-piece'],
                  ['Qrcode', 'fa-qrcode'],
                  ['Question', 'fa-question'],
                  ['Question-circle', 'fa-question-circle'],
                  ['Quote-left', 'fa-quote-left'],
                  ['Quote-right', 'fa-quote-right'],
                  ['Random', 'fa-random'],
                  ['Recycle', 'fa-recycle'],
                  ['Registered', 'fa-registered'],
                  ['Reply', 'fa-reply'],
                  ['Reply-all', 'fa-reply-all'],
                  ['Road', 'fa-road'],
                  ['Rocket', 'fa-rocket'],
                  ['Rss', 'fa-rss'],
                  ['Rss-square', 'fa-rss-square'],
                  ['Rupee-sign', 'fa-rupee'],
                  ['Save', 'fa-save'],
                  ['Search', 'fa-search'],
                  ['Server', 'fa-server'],
                  ['Share', 'fa-share'],
                  ['Share-alt', 'fa-share-alt'],
                  ['Share-alt-square', 'fa-share-alt-square'],
                  ['Share-square', 'fa-share-square'],
                  ['Shield', 'fa-shield'],
                  ['Ship', 'fa-ship'],
                  ['Signal', 'fa-signal'],
                  ['Sitemap', 'fa-sitemap'],
                  ['Sort', 'fa-sort'],
                  ['Space-shuttle', 'fa-space-shuttle'],
                  ['Spinner', 'fa-spinner'],
                  ['Spray-can', 'fa-spray-can'],
                  ['Square', 'fa-square'],
                  ['Star', 'fa-star'],
                  ['Star-half', 'fa-star-half'],
                  ['Step-backward', 'fa-step-backward'],
                  ['Step-forward', 'fa-step-forward'],
                  ['Stethoscope', 'fa-stethoscope'],
                  ['Sticky-note', 'fa-sticky-note'],
                  ['Stop', 'fa-stop'],
                  ['Stop-circle', 'fa-stop-circle'],
                  ['Subscript', 'fa-subscript'],
                  ['Subway', 'fa-subway'],
                  ['Sun', 'fa-sun'],
                  ['Superscript', 'fa-superscript'],
                  ['Support', 'fa-support'],
                  ['Tablet', 'fa-tablet'],
                  ['Tablets', 'fa-tablets'],
                  ['Tag', 'fa-tag'],
                  ['Tags', 'fa-tags'],
                  ['Tasks', 'fa-tasks'],
                  ['Taxi', 'fa-taxi'],
                  ['Terminal', 'fa-terminal'],
                  ['Text-height', 'fa-text-height'],
                  ['Text-width', 'fa-text-width'],
                  ['Th', 'fa-th'],
                  ['Th-large', 'fa-th-large'],
                  ['Th-list', 'fa-th-list'],
                  ['Thumbs-down', 'fa-thumbs-down'],
                  ['Thumbs-up', 'fa-thumbs-up'],
                  ['Thumbtack', 'fa-thumbtack'],
                  ['Times', 'fa-times'],
                  ['Times-circle', 'fa-times-circle'],
                  ['Toggle-off', 'fa-toggle-off'],
                  ['Toggle-on', 'fa-toggle-on'],
                  ['Toilet-paper', 'fa-toilet-paper'],
                  ['Toolbox', 'fa-toolbox'],
                  ['Tools', 'fa-tools'],
                  ['Tractor', 'fa-tractor'],
                  ['Trademark', 'fa-trademark'],
                  ['Train', 'fa-train'],
                  ['Trash', 'fa-trash'],
                  ['Tree', 'fa-tree'],
                  ['Trophy', 'fa-trophy'],
                  ['Truck', 'fa-truck'],
                  ['Tty', 'fa-tty'],
                  ['Tv', 'fa-tv'],
                  ['Twitter', 'fa-twitter'],
                  ['Umbrella', 'fa-umbrella'],
                  ['Underline', 'fa-underline'],
                  ['Undo', 'fa-undo'],
                  ['Undo-alt', 'fa-undo-alt'],
                  ['Universal-access', 'fa-universal-access'],
                  ['University', 'fa-university'],
                  ['Unlink', 'fa-unlink'],
                  ['Unlock', 'fa-unlock'],
                  ['Unlock-alt', 'fa-unlock-alt'],
                  ['Upload', 'fa-upload'],
                  ['User', 'fa-user'],
                  ['User-md', 'fa-user-md'],
                  ['Users', 'fa-users'],
                  ['Volume-down', 'fa-volume-down'],
                  ['Volume-off', 'fa-volume-off'],
                  ['Volume-up', 'fa-volume-up'],
                  ['Walking', 'fa-walking'],
                  ['Wallet', 'fa-wallet'],
                  ['Warehouse', 'fa-warehouse'],
                  ['Water', 'fa-water'],
                  ['Warning', 'fa-warning'],
                  ['Weight', 'fa-weight'],
                  ['Weight-hanging', 'fa-weight-hanging'],
                  ['Wheelchair', 'fa-wheelchair'],
                  ['Wifi', 'fa-wifi'],
                  ['Wind', 'fa-windows'],
                  ['Window-close', 'fa-window-close'],
                  ['Window-maximize', 'fa-window-maximize'],
                  ['Window-minimize', 'fa-window-minimize'],
                  ['Window-restore', 'fa-window-restore'],
                  ['Wine-bottle', 'fa-wine-bottle'],
                  ['Wine-glass', 'fa-glass'],
                  ['Wrench', 'fa-wrench'],
            ];
    }

}
