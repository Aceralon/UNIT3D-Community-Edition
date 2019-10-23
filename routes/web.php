<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(['middleware' => 'language'], function () {

    /*
    |------------------------------------------
    | Website (Not Authorized)
    |------------------------------------------
    */
    Route::group(['before' => 'auth', 'middleware' => 'guest'], function () {
        // Authentication
        Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('login', 'Auth\LoginController@login')->name('');

        // Password Reset
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('');
        Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

        // Registration
        Route::get('/register/{code?}', 'Auth\RegisterController@registrationForm')->name('registrationForm');
        Route::post('/register/{code?}', 'Auth\RegisterController@register')->name('register');

        // Application Signup
        Route::get('/application', 'Auth\ApplicationController@create')->name('application.create');
        Route::post('/application', 'Auth\ApplicationController@store')->name('application.store');

        // Activation
        Route::get('/activate/{token}', 'Auth\ActivationController@activate')->name('activate');

        // Forgot Username
        Route::get('username/reminder', 'Auth\ForgotUsernameController@showForgotUsernameForm')->name('username.request');
        Route::post('username/reminder', 'Auth\ForgotUsernameController@sendUsernameReminder')->name('username.email');
    });

    Route::group(['before' => 'auth'], function () {
        // Announce (Pass Key Auth)
        Route::get('/announce/{passkey}', 'AnnounceController@announce')->name('announce');

        // RSS (RSS Key Auth)
        Route::get('/rss/{id}.{rsskey}', 'RssController@show')->name('rss.show.rsskey');
        Route::get('/torrent/download/{id}.{rsskey}', 'TorrentController@download')->name('torrent.download.rsskey');
    });

    /*
    |------------------------------------------
    | Website (When Authorized)
    |------------------------------------------
    */
    Route::group(['middleware' => ['auth', 'twostep', 'banned']], function () {

        // General
        Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('/', 'HomeController@index')->name('home.index');

        // RSS System
        Route::group(['prefix' => 'rss'], function () {
            Route::get('/{hash?}', 'RssController@index')->name('rss.index');
            Route::get('/create', 'RssController@create')->name('rss.create');
            Route::post('/store', 'RssController@store')->name('rss.store');
            Route::get('/{id}/edit', 'RssController@edit')->name('rss.edit');
            Route::patch('/{id}/update', 'RssController@update')->name('rss.update');
            Route::delete('/{id}/destroy', 'RssController@destroy')->name('rss.destroy');
        });

        // Two Step Auth
        Route::group(['prefix' => 'twostep'], function () {
            Route::get('/needed', 'Auth\TwoStepController@showVerification')->name('verificationNeeded');
            Route::post('/verify', 'Auth\TwoStepController@verify')->name('verify');
            Route::post('/resend', 'Auth\TwoStepController@resend')->name('resend');
        });

        // Articles
        Route::group(['prefix' => 'articles'], function () {
            Route::get('/', 'ArticleController@index')->name('articles.index');
            Route::get('/{id}', 'ArticleController@show')->name('articles.show');
        });

        // Bonus System
        Route::group(['prefix' => 'bonus'], function () {
            Route::get('/', 'BonusController@bonus')->name('bonus');
            Route::get('/gifts', 'BonusController@gifts')->name('bonus_gifts');
            Route::get('/tips', 'BonusController@tips')->name('bonus_tips');
            Route::get('/store', 'BonusController@store')->name('bonus_store');
            Route::get('/gift', 'BonusController@gift')->name('bonus_gift');
            Route::post('/exchange/{id}', 'BonusController@exchange')->name('bonus_exchange');
            Route::post('/gift', 'BonusController@sendGift')->name('bonus_send_gift');
        });

        // Bookmarks
        Route::group(['prefix' => 'bookmarks'], function () {
            Route::post('/{id}/store', 'BookmarkController@store')->name('bookmarks.store');
            Route::delete('/{id}/destroy', 'BookmarkController@destroy')->name('bookmarks.destroy');
        });

        // Reports System
        Route::group(['prefix' => 'reports'], function () {
            Route::post('/torrent/{id}', 'ReportController@torrent')->name('report_torrent');
            Route::post('/request/{id}', 'ReportController@request')->name('report_request');
            Route::post('/user/{id}', 'ReportController@user')->name('report_user');
        });

        // Categories System
        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', 'CategoryController@index')->name('categories.index');
            Route::get('/{id}', 'CategoryController@show')->name('categories.show');
        });

        // Contact Us System
        Route::group(['prefix' => 'contact'], function () {
            Route::get('/', 'ContactController@index')->name('contact.index');
            Route::post('/store', 'ContactController@sotore')->name('contact.store');
        });

        // Pages System
        Route::group(['prefix' => 'pages'], function () {
            Route::get('/{id}', 'PageController@show')->name('pages.show');
            Route::get('/staff', 'PageController@staff')->name('staff');
            Route::get('/internal', 'PageController@internal')->name('internal');
            Route::get('/blacklist', 'PageController@blacklist')->name('blacklist');
            Route::get('/aboutus', 'PageController@about')->name('about');
            Route::get('/emaillist', 'PageController@emailList')->name('emaillist');
        });

        // Comments
        Route::group(['prefix' => 'comments'], function () {
            Route::post('/article/{id}', 'CommentController@article')->name('comment_article');
            Route::post('/torrent/{id}', 'CommentController@torrent')->name('comment_torrent');
            Route::get('/thanks/{id}', 'CommentController@quickthanks')->name('comment_thanks');
            Route::post('/request/{id}', 'CommentController@request')->name('comment_request');
            Route::post('/playlist/{id}', 'CommentController@playlist')->name('comment_playlist');
            Route::post('/edit/{comment_id}', 'CommentController@editComment')->name('comment_edit');
            Route::get('/delete/{comment_id}', 'CommentController@deleteComment')->name('comment_delete');
        });

        //Extra-Stats
        Route::group(['prefix' => 'stats'], function () {
            Route::get('/', 'StatsController@index')->name('stats');
            Route::get('/user/uploaded', 'StatsController@uploaded')->name('uploaded');
            Route::get('/user/downloaded', 'StatsController@downloaded')->name('downloaded');
            Route::get('/user/seeders', 'StatsController@seeders')->name('seeders');
            Route::get('/user/leechers', 'StatsController@leechers')->name('leechers');
            Route::get('/user/uploaders', 'StatsController@uploaders')->name('uploaders');
            Route::get('/user/bankers', 'StatsController@bankers')->name('bankers');
            Route::get('/user/seedtime', 'StatsController@seedtime')->name('seedtime');
            Route::get('/user/seedsize', 'StatsController@seedsize')->name('seedsize');
            Route::get('/torrent/seeded', 'StatsController@seeded')->name('seeded');
            Route::get('/torrent/leeched', 'StatsController@leeched')->name('leeched');
            Route::get('/torrent/completed', 'StatsController@completed')->name('completed');
            Route::get('/torrent/dying', 'StatsController@dying')->name('dying');
            Route::get('/torrent/dead', 'StatsController@dead')->name('dead');
            Route::get('/request/bountied', 'StatsController@bountied')->name('bountied');
            Route::get('/groups', 'StatsController@groups')->name('groups');
            Route::get('/groups/group/{id}', 'StatsController@group')->name('group');
            Route::get('/languages', 'StatsController@languages')->name('languages');
        });

        // Private Messages System
        Route::group(['prefix' => 'mail'], function () {
            Route::post('/searchPMInbox', 'PrivateMessageController@searchPMInbox')->name('searchPMInbox');
            Route::post('/searchPMOutbox', 'PrivateMessageController@searchPMOutbox')->name('searchPMOutbox');
            Route::get('/inbox', 'PrivateMessageController@getPrivateMessages')->name('inbox');
            Route::get('/message/{id}', 'PrivateMessageController@getPrivateMessageById')->name('message');
            Route::get('/outbox', 'PrivateMessageController@getPrivateMessagesSent')->name('outbox');
            Route::get('/create/{receiver_id}/{username}', 'PrivateMessageController@makePrivateMessage')->name('create');
            Route::get('/mark-all-read', 'PrivateMessageController@markAllAsRead')->name('mark-all-read');
            Route::post('/send', 'PrivateMessageController@sendPrivateMessage')->name('send-pm');
            Route::post('/reply/{id}', 'PrivateMessageController@replyPrivateMessage')->name('reply-pm');
            Route::post('/delete/{id}', 'PrivateMessageController@deletePrivateMessage')->name('delete-pm');
        });

        // Requests
        Route::group(['prefix' => 'requests'], function () {
            Route::get('/filter', 'RequestController@faceted');
            Route::get('/', 'RequestController@requests')->name('requests');
            Route::get('/add/{title?}/{imdb?}/{tmdb?}', 'RequestController@addRequestForm')->name('add_request_form');
            Route::post('/add', 'RequestController@addRequest')->name('add_request');
            Route::get('/{id}/edit', 'RequestController@editRequestForm')->name('edit_request_form');
            Route::post('/{id}/edit', 'RequestController@editRequest')->name('edit_request');
            Route::get('/{id}{hash?}', 'RequestController@request')->name('request');
            Route::get('/{id}/accept', 'RequestController@approveRequest')->name('approveRequest');
            Route::post('/{id}/delete', 'RequestController@deleteRequest')->name('deleteRequest');
            Route::post('/{id}/fill', 'RequestController@fillRequest')->name('fill_request');
            Route::get('/{id}/reject', 'RequestController@rejectRequest')->name('rejectRequest');
            Route::post('/{id}/vote', 'RequestController@addBonus')->name('add_votes');
            Route::post('/{id}/claim', 'RequestController@claimRequest')->name('claimRequest');
            Route::get('/{id}/unclaim', 'RequestController@unclaimRequest')->name('unclaimRequest');
        });

        // Torrent
        Route::group(['prefix' => 'torrents'], function () {
            Route::get('/feedizeTorrents/{type}', 'TorrentController@feedize')->name('feedizeTorrents')->middleware('modo');
            Route::get('/filterTorrents', 'TorrentController@faceted');
            Route::get('/filterSettings', 'TorrentController@filtered');
            Route::get('/torrents', 'TorrentController@torrents')->name('torrents');
            Route::get('/torrents/{id}{hash?}', 'TorrentController@torrent')->name('torrent');
            Route::get('/torrents/{id}/peers', 'TorrentController@peers')->name('peers');
            Route::get('/torrents/{id}/history', 'TorrentController@history')->name('history');
            Route::get('/upload/{title?}/{imdb?}/{tmdb?}', 'TorrentController@uploadForm')->name('upload_form');
            Route::post('/upload', 'TorrentController@upload')->name('upload');
            Route::get('/download_check/{id}', 'TorrentController@downloadCheck')->name('download_check');
            Route::get('/download/{id}', 'TorrentController@download')->name('download');
            Route::get('/torrents/cards', 'TorrentController@cardLayout')->name('cards');
            Route::get('/torrents/groupings', 'TorrentController@groupingLayout')->name('groupings');
            Route::post('/torrents/delete', 'TorrentController@deleteTorrent')->name('delete');
            Route::get('/torrents/{id}/edit', 'TorrentController@editForm')->name('edit_form');
            Route::post('/torrents/{id}/edit', 'TorrentController@edit')->name('edit');
            Route::get('/torrents/{id}/torrent_fl', 'TorrentController@grantFL')->name('torrent_fl');
            Route::get('/torrents/{id}/torrent_doubleup', 'TorrentController@grantDoubleUp')->name('torrent_doubleup');
            Route::get('/torrents/{id}/bumpTorrent', 'TorrentController@bumpTorrent')->name('bumpTorrent');
            Route::get('/torrents/{id}/torrent_sticky', 'TorrentController@sticky')->name('torrent_sticky');
            Route::get('/torrents/{id}/torrent_feature', 'TorrentController@grantFeatured')->name('torrent_feature');
            Route::get('/torrents/{id}/reseed', 'TorrentController@reseedTorrent')->name('reseed');
            Route::post('/torrents/{id}/tip_uploader', 'BonusController@tipUploader')->name('tip_uploader');
            Route::get('/torrents/{id}/freeleech_token', 'TorrentController@freeleechToken')->name('freeleech_token');
            Route::get('/torrents/similar/{category_id}.{tmdb}', 'TorrentController@similar')->name('torrents.similar');
        });

        // Achievements
        Route::get('/achievements', 'AchievementsController@index')->name('achievements.index');
        Route::get('/{username}/achievements', 'AchievementsController@show')->name('achievements.show');

        // Warnings
        Route::group(['prefix' => 'warnings'], function () {
            Route::get('/{username}', 'WarningController@show')->name('warnings.show');
            Route::get('/{id}/deactivate', 'WarningController@deactivate')->name('deactivateWarning');
            Route::get('/{username}/mass-deactivate', 'WarningController@deactivateAllWarnings')->name('massDeactivateWarnings');
            Route::delete('/{id}', 'WarningController@deleteWarning')->name('deleteWarning');
            Route::get('/{username}/mass-delete', 'WarningController@deleteAllWarnings')->name('massDeleteWarnings');
            Route::get('/{id}/restore', 'WarningController@restoreWarning')->name('restoreWarning');
        });

        // User
        Route::group(['prefix' => 'users'], function () {
            Route::get('/{username}', 'UserController@show')->name('users.show');
            Route::get('/{username}/edit', 'UserController@editProfileForm')->name('user_edit_profile_form');
            Route::post('/{username}/edit', 'UserController@editProfile')->name('user_edit_profile');
            Route::post('/{username}/photo', 'UserController@changePhoto')->name('user_change_photo');
            Route::get('/{username}/activate/{token}', 'UserController@activate')->name('user_activate');
            Route::post('/{username}/about', 'UserController@changeAbout')->name('user_change_about');
            Route::post('/{username}/photo', 'UserController@changeTitle')->name('user_change_title');
            Route::get('/{username}/banlog', 'UserController@getBans')->name('banlog');
            Route::post('/{username}/userFilters', 'UserController@myFilter')->name('myfilter');
            Route::get('/{username}/downloadHistoryTorrents', 'UserController@downloadHistoryTorrents')->name('download_history_torrents');
            Route::get('/{username}/seeds', 'UserController@seeds')->name('user_seeds');
            Route::get('/{username}/resurrections', 'UserController@resurrections')->name('user_resurrections');
            Route::get('/{username}/requested', 'UserController@requested')->name('user_requested');
            Route::get('/{username}/active', 'UserController@active')->name('user_active');
            Route::get('/{username}/torrents', 'UserController@torrents')->name('user_torrents');
            Route::get('/{username}/uploads', 'UserController@uploads')->name('user_uploads');
            Route::get('/{username}/downloads', 'UserController@downloads')->name('user_downloads');
            Route::get('/{username}/unsatisfieds', 'UserController@unsatisfieds')->name('user_unsatisfieds');
            Route::get('/{username}/topics', 'UserController@topics')->name('user_topics');
            Route::get('/{username}/posts', 'UserController@posts')->name('user_posts');
            Route::get('/{username}/followers', 'UserController@followers')->name('user_followers');
            Route::get('/{username}/bookmarks', 'BookmarkController@index')->name('bookmarks.index');

            Route::get('/{username}/settings', 'UserController@settings')->name('user_settings');
            Route::get('/{username}/settings/privacy{hash?}', 'UserController@privacy')->name('user_privacy');
            Route::get('/{username}/settings/profile', 'UserController@profile')->name('user_profile');
            Route::get('/{username}/settings/security{hash?}', 'UserController@security')->name('user_security');
            Route::get('/{username}/settings/notification{hash?}', 'UserController@notification')->name('user_notification');
            Route::post('/{username}/settings/change_settings', 'UserController@changeSettings')->name('change_settings');
            Route::post('/{username}/settings/change_password', 'UserController@changePassword')->name('change_password');
            Route::post('/{username}/settings/change_email', 'UserController@changeEmail')->name('change_email');
            Route::post('/{username}/settings/change_pid', 'UserController@changePID')->name('change_pid');
            Route::post('/{username}/settings/change_rid', 'UserController@changeRID')->name('change_rid');
            Route::get('/{username}/settings/notification/disable', 'UserController@disableNotifications')->name('notification_disable');
            Route::get('/{username}/settings/notification/enable', 'UserController@enableNotifications')->name('notification_enable');
            Route::post('/{username}/settings/notification/account', 'UserController@changeAccountNotification')->name('notification_account');
            Route::post('/{username}/settings/notification/following', 'UserController@changeFollowingNotification')->name('notification_following');
            Route::post('/{username}/settings/notification/forum', 'UserController@changeForumNotification')->name('notification_forum');
            Route::post('/{username}/settings/notification/subscription', 'UserController@changeSubscriptionNotification')->name('notification_subscription');
            Route::post('/{username}/settings/notification/mention', 'UserController@changeMentionNotification')->name('notification_mention');
            Route::post('/{username}/settings/notification/torrent', 'UserController@changeTorrentNotification')->name('notification_torrent');
            Route::post('/{username}/settings/notification/bon', 'UserController@changeBonNotification')->name('notification_bon');
            Route::post('/{username}/settings/notification/request', 'UserController@changeRequestNotification')->name('notification_request');
            Route::post('/{username}/settings/privacy/profile', 'UserController@changeProfile')->name('privacy_profile');
            Route::post('/{username}/settings/privacy/forum', 'UserController@changeForum')->name('privacy_forum');
            Route::post('/{username}/settings/privacy/torrent', 'UserController@changeTorrent')->name('privacy_torrent');
            Route::post('/{username}/settings/privacy/follower', 'UserController@changeFollower')->name('privacy_follower');
            Route::post('/{username}/settings/privacy/achievement', 'UserController@changeAchievement')->name('privacy_achievement');
            Route::post('/{username}/settings/privacy/request', 'UserController@changeRequest')->name('privacy_request');
            Route::post('/{username}/settings/privacy/other', 'UserController@changeOther')->name('privacy_other');
            Route::post('/{username}/settings/change_twostep', 'UserController@changeTwoStep')->name('change_twostep');
            Route::get('/{username}/settings/hidden', 'UserController@makeHidden')->name('user_hidden');
            Route::get('/{username}/settings/visible', 'UserController@makeVisible')->name('user_visible');
            Route::get('/{username}/settings/private', 'UserController@makePrivate')->name('user_private');
            Route::get('/{username}/settings/public', 'UserController@makePublic')->name('user_public');
            Route::get('/{username}/invites', 'InviteController@invites')->name('user_invites');
            Route::post('/accept-rules', 'UserController@acceptRules')->name('accept.rules');

            Route::get('/{username}/seedboxes', 'SeedboxController@index')->name('seedboxes.index');
            Route::post('/{username}/seedboxes', 'SeedboxController@store')->name('seedboxes.store');
            Route::delete('/{username}/seedboxes/{id}', 'SeedboxController@destroy')->name('seedboxes.destroy');
            Route::get('/{username}/wishlist', 'UserController@wishes')->name('user_wishlist');
        });

        // Wishlist System
        Route::post('/wish/{uid}', 'WishController@store')->name('wish-store');
        Route::get('/wish/{uid}/delete/{id}', 'WishController@destroy')->name('wish-delete');

        // Follow System
        Route::post('/follow/{username}', 'FollowController@store')->name('follow.store');
        Route::delete('/follow/{username}', 'FollowController@destroy')->name('follow.destroy');

        //Thank System
        Route::get('/thanks/{id}', 'ThankController@store')->name('thanks.store');

        // User Language
        Route::get('/{locale}/back', 'LanguageController@back')->name('back');

        // Invite System
        Route::get('/invite', 'InviteController@invite')->name('invite');
        Route::post('/invite', 'InviteController@process')->name('process');
        Route::post('/resendinvite/{id}', 'InviteController@reProcess')->name('reProcess');

        // Poll System
        Route::group(['prefix' => 'polls'], function () {
            Route::get('/', 'PollController@index')->name('polls');
            Route::get('/{slug}', 'PollController@show')->name('poll');
            Route::post('/vote', 'PollController@vote')->middleware('check_ip');
            Route::get('/{slug}/result', 'PollController@result')->name('poll_results');
        });

        // Graveyard System
        Route::group(['prefix' => 'graveyard'], function () {
            Route::get('/filter', 'GraveyardController@faceted');
            Route::get('/', 'GraveyardController@index')->name('graveyard.index');
            Route::post('/{id}/store', 'GraveyardController@store')->name('graveyard.store');
            Route::delete('/{id}/destroy', 'GraveyardController@destroy')->name('graveyard.destroy');
        });

        // Notifications System
        Route::group(['prefix' => 'notifications'], function () {
            Route::get('/filter', 'NotificationController@faceted');
            Route::get('/', 'NotificationController@index')->name('notifications.index');
            Route::get('/{id}', 'NotificationController@show')->name('notifications.show');
            Route::get('/{id}/update', 'NotificationController@update')->name('notifications.update');
            Route::get('/updateall', 'NotificationController@updateAll')->name('notifications.updateall');
            Route::get('/{id}/destroy', 'NotificationController@destroy')->name('notifications.destroy');
            Route::get('/destroyall', 'NotificationController@destroyAll')->name('notifications.destroyall');
        });

        // Albums System
        Route::group(['prefix' => 'albums'], function () {
            Route::get('/', 'AlbumController@index')->name('albums.index');
            Route::get('/create', 'AlbumController@create')->name('albums.create');
            Route::post('/store', 'AlbumController@store')->name('albums.store');
            Route::get('/{id}', 'AlbumController@show')->name('albums.show');
            Route::delete('/{id}/destroy', 'AlbumController@destroy')->name('albums.destroy');
        });

        // Images System
        Route::group(['prefix' => 'images'], function () {
            Route::get('/{id}/create', 'ImageController@create')->name('images.create');
            Route::post('/store', 'ImageController@store')->name('images.store');
            Route::get('/{id}/download', 'ImageController@download')->name('images.download');
            Route::delete('/{id}/destroy', 'ImageController@destroy')->name('images.destroy');
        });

        // Playlist System
        Route::group(['prefix' => 'playlists'], function () {
            Route::get('/', 'PlaylistController@index')->name('playlists.index');
            Route::get('/create', 'PlaylistController@create')->name('playlists.create');
            Route::post('/store', 'PlaylistController@store')->name('playlists.store');
            Route::get('/{id}', 'PlaylistController@show')->name('playlists.show');
            Route::get('/{id}/edit', 'PlaylistController@edit')->name('playlists.edit');
            Route::patch('/{id}/update', 'PlaylistController@update')->name('playlists.update');
            Route::delete('/{id}/destroy', 'PlaylistController@destroy')->name('playlists.destroy');
            Route::post('/attach', 'PlaylistTorrentController@store')->name('playlists.attach');
            Route::delete('/{id}/detach', 'PlaylistTorrentController@destroy')->name('playlists.detach');
        });
    });

    /*
    |------------------------------------------
    | ChatBox Routes Group (When Authorized)
    |------------------------------------------
    */
    Route::group(['prefix' => 'chatbox', 'middleware' => ['auth', 'twostep', 'banned'], 'namespace' => 'API'], function () {
        Route::get('/', 'ChatController@index');
        Route::get('chatrooms', 'ChatController@fetchChatrooms');
        Route::post('change-chatroom', 'ChatController@changeChatroom');
        Route::get('messages', 'ChatController@fetchMessages');
        Route::post('messages', 'ChatController@sendMessage');
    });

    /*
    |------------------------------------------
    | Community Routes Group (When Authorized)
    |------------------------------------------
    */
    Route::group(['prefix' => 'forums', 'middleware' => ['auth', 'twostep', 'banned']], function () {

        // Display Forum Index
        Route::get('/', 'ForumController@index')->name('forum_index');

        // Search Forums
        Route::get('/subscriptions', 'ForumController@subscriptions')->name('forum_subscriptions');
        Route::get('/latest/topics', 'ForumController@latestTopics')->name('forum_latest_topics');
        Route::get('/latest/posts', 'ForumController@latestPosts')->name('forum_latest_posts');

        Route::get('/search', 'ForumController@search')->name('forum_search');
        Route::get('/search', 'ForumController@search')->name('forum_search_form');

        // Display Forum Categories
        Route::get('/category/{id}', 'ForumController@category')->name('forum_category');
        // Display Topics
        Route::get('/forum/{id}', 'ForumController@display')->name('forum_display');
        // Create New Topic
        Route::get('/forum/{id}/new-topic', 'ForumController@addForm')->name('forum_new_topic_form');
        Route::post('/forum/{id}/new-topic', 'ForumController@newTopic')->name('forum_new_topic');
        // View Topic
        Route::get('/topic/{id}', 'ForumController@topic')->name('forum_topic');
        // Close Topic
        Route::get('/topic/{id}/close', 'ForumController@closeTopic')->name('forum_close');
        // Open Topic
        Route::get('/topic/{id}/open', 'ForumController@openTopic')->name('forum_open');
        //
        Route::post('/posts/{id}/tip_poster', 'BonusController@tipPoster')->name('tip_poster');
        // Edit Post
        Route::get('/posts/{id}/post-{postId}/edit', 'ForumController@postEditForm')->name('forum_post_edit_form');
        Route::post('/posts/{postId}/edit', 'ForumController@postEdit')->name('forum_post_edit');
        // Delete Post
        Route::get('/posts/{postId}/delete', 'ForumController@postDelete')->name('forum_post_delete');
        // Reply To Topic
        Route::post('/topic/{id}/reply', 'ForumController@reply')->name('forum_reply');
        // Edit Topic
        Route::get('/topic/{id}/edit', 'ForumController@editForm')->name('forum_edit_topic_form');
        Route::post('/topic/{id}/edit', 'ForumController@editTopic')->name('forum_edit_topic');
        // Delete Topic
        Route::get('/topic/{id}/delete', 'ForumController@deleteTopic')->name('forum_delete_topic');
        // Pin Topic
        Route::get('/topic/{id}/pin', 'ForumController@pinTopic')->name('forum_pin_topic');
        // Unpin Topic
        Route::get('/topic/{id}/unpin', 'ForumController@unpinTopic')->name('forum_unpin_topic');

        // Like - Dislike System
        Route::any('/like/post/{postId}', 'LikeController@store')->name('like');
        Route::any('/dislike/post/{postId}', 'LikeController@destroy')->name('dislike');

        // Subscription System
        Route::get('/subscribe/topic/{route}.{topic}', 'SubscriptionController@subscribeTopic')->name('subscribe_topic');
        Route::get('/unsubscribe/topic/{route}.{topic}', 'SubscriptionController@unsubscribeTopic')->name('unsubscribe_topic');
        Route::get('/subscribe/forum/{route}.{forum}', 'SubscriptionController@subscribeForum')->name('subscribe_forum');
        Route::get('/unsubscribe/forum/{route}.{forum}', 'SubscriptionController@unsubscribeForum')->name('unsubscribe_forum');

        // Topic Label System
        Route::get('/topic/{id}/approved', 'ForumController@approvedTopic')->name('forum_approved')->middleware('modo');
        Route::get('/topic/{id}/denied', 'ForumController@deniedTopic')->name('forum_denied')->middleware('modo');
        Route::get('/topic/{id}/solved', 'ForumController@solvedTopic')->name('forum_solved')->middleware('modo');
        Route::get('/topic/{id}/invalid', 'ForumController@invalidTopic')->name('forum_invalid')->middleware('modo');
        Route::get('/topic/{id}/bug', 'ForumController@bugTopic')->name('forum_bug')->middleware('modo');
        Route::get('/topic/{id}/suggestion', 'ForumController@suggestionTopic')->name('forum_suggestion')->middleware('modo');
        Route::get('/topic/{id}/implemented', 'ForumController@implementedTopic')->name('forum_implemented')->middleware('modo');
    });

    /*
    |-----------------------------------------------------------------
    | Staff Dashboard Routes Group (When Authorized And A Staff Group)
    |-----------------------------------------------------------------
    */
    Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'twostep', 'modo', 'banned'], 'namespace' => 'Staff'], function () {

        // Staff Dashboard
        Route::get('/', 'HomeController@index')->name('staff.dashboard.index');

        // RSS System
        Route::group(['prefix' => 'rss'], function () {
            Route::get('/', 'RssController@index')->name('staff.rss.index');
            Route::get('/create', 'RssController@create')->name('staff.rss.create');
            Route::post('/store', 'RssController@store')->name('staff.rss.store');
            Route::get('/{id}/edit', 'RssController@edit')->name('staff.rss.edit');
            Route::patch('/{id}/update', 'RssController@update')->name('staff.rss.update');
            Route::delete('/{id}/destroy', 'RssController@destroy')->name('staff.rss.destroy');
        });

        // Chat Bots
        Route::group(['prefix' => 'bots'], function () {
            Route::get('/', 'BotsController@index')->name('staff.bots.index');
            Route::get('/{id}/edit', 'BotsController@edit')->name('staff.bots.edit');
            Route::patch('/{id}/update', 'BotsController@update')->name('staff.bots.update');
            Route::delete('/{id}/destroy', 'BotsController@destroy')->name('staff.bots.destroy');
            Route::get('/{id}/disable', 'BotsController@disable')->name('staff.bots.disable');
            Route::get('/{id}/enable', 'BotsController@enable')->name('staff.bots.enable');
        });

        // Codebase Version Check
        Route::get('/check-update', 'VersionController@checkVersion');

        // Ban System
        Route::group(['prefix' => 'bans'], function () {
            Route::get('/', 'BanController@getBans')->name('getBans');
            Route::post('/ban/{id}', 'BanController@ban')->name('ban');
            Route::post('/unban/{id}', 'BanController@unban')->name('unban');
        });

        // Flush Ghost Peers
        Route::get('/flush', 'FlushController@deleteOldPeers')->name('flush');

        // User Tools
        Route::get('/user_search', 'UserController@members')->name('user_search');
        Route::get('/user_results', 'UserController@userSearch')->name('user_results');
        Route::get('/user_edit/{username}', 'UserController@userSettings')->name('user_setting');
        Route::post('/user_edit/{username}/edit', 'UserController@userEdit')->name('user_edit');
        Route::post('/user_edit/{username}/permissions', 'UserController@userPermissions')->name('user_permissions');
        Route::get('/user_delete/{id}', 'UserController@userDelete')->name('user_delete');
        Route::post('/user_edit/{username}/password', 'UserController@userPassword')->name('user_password');

        // Moderation
        Route::get('/torrents', 'TorrentController@index')->name('staff_torrent_index');
        Route::get('/moderation', 'ModerationController@moderation')->name('moderation');
        Route::get('/moderation/{id}/approve', 'ModerationController@approve')->name('moderation_approve');
        Route::post('/moderation/reject', 'ModerationController@reject')->name('moderation_reject');
        Route::post('/moderation/postpone', 'ModerationController@postpone')->name('moderation_postpone');
        Route::get('/torrent_search', 'TorrentController@search')->name('torrent-search');

        // Request section
        Route::get('/request/{id}/reset', 'ModerationController@resetRequest')->name('resetRequest');

        // User Staff Notes
        Route::get('/notes', 'NoteController@getNotes')->name('getNotes');
        Route::post('/note/{id}', 'NoteController@postNote')->name('postNote');
        Route::get('/note/{id}', 'NoteController@deleteNote')->name('deleteNote');

        // Reports
        Route::get('/reports', 'ReportController@getReports')->name('getReports');
        Route::get('/reports/{report_id}', 'ReportController@getReport')->name('getReport');
        Route::post('/reports/{report_id}/solve', 'ReportController@solveReport')->name('solveReport');

        // Categories
        Route::group(['prefix' => 'types'], function () {
            Route::get('/', 'CategoryController@index')->name('staff.categories.index');
            Route::get('/create', 'CategoryController@create')->name('staff.categories.create');
            Route::post('/store', 'CategoryController@store')->name('staff.categories.store');
            Route::get('/{id}/edit', 'CategoryController@edit')->name('staff.categories.edit');
            Route::patch('/{id}/update', 'CategoryController@update')->name('staff.categories.update');
            Route::delete('/{id}/destroy', 'CategoryController@destroy')->name('staff.categories.destroy');
        });

        // Types
        Route::group(['prefix' => 'types'], function () {
            Route::get('/', 'TypeController@index')->name('staff.types.index');
            Route::get('/create', 'TypeController@create')->name('staff.types.create');
            Route::post('/store', 'TypeController@store')->name('staff.types.store');
            Route::get('//{id}/edit', 'TypeController@edit')->name('staff.types.edit');
            Route::patch('/{id}/update', 'TypeController@update')->name('staff.types.update');
            Route::delete('/{id}/destroy', 'TypeController@destroy')->name('staff.types.destroy');
        });

        // Forum
        Route::group(['prefix' => 'forums'], function () {
            Route::get('/', 'ForumController@index')->name('staff.forums.index');
            Route::get('/create', 'ForumController@create')->name('staff.forums.create');
            Route::post('/store', 'ForumController@store')->name('staff.forums.store');
            Route::get('/{id}/edit', 'ForumController@edit')->name('staff.forums.edit');
            Route::post('/{id}/update', 'ForumController@update')->name('staff.forums.update');
            Route::get('/{id}/destroy', 'ForumController@destory')->name('staff.forums.destroy');
        });

        //Pages
        Route::get('/pages', 'PageController@index')->name('staff_page_index');
        Route::get('/pages/new', 'PageController@addForm')->name('staff_page_add_form');
        Route::post('/pages/new', 'PageController@add')->name('staff_page_add');
        Route::get('/pages/edit/{id}', 'PageController@editForm')->name('staff_page_edit_form');
        Route::post('/pages/edit/{id}', 'PageController@edit')->name('staff_page_edit');
        Route::get('/pages/delete/{id}', 'PageController@delete')->name('staff_page_delete');

        // Articles
        Route::get('/articles', 'ArticleController@index')->name('staff_article_index');
        Route::get('/articles/new', 'ArticleController@addForm')->name('staff_article_add_form');
        Route::post('/articles/new', 'ArticleController@add')->name('staff_article_add');
        Route::get('/articles/edit/{id}', 'ArticleController@editForm')->name('staff_article_edit_form');
        Route::post('/articles/edit/{id}', 'ArticleController@edit')->name('staff_article_edit');
        Route::get('/articles/delete/{id}', 'ArticleController@delete')->name('staff_article_delete');

        // Groups
        Route::get('/groups', 'GroupsController@index')->name('staff_groups_index');
        Route::get('/groups/add', 'GroupsController@addForm')->name('staff_groups_add_form');
        Route::post('/groups/add', 'GroupsController@add')->name('staff_groups_add');
        Route::get('/groups/edit/{group}.{id}', 'GroupsController@editForm')->name('staff_groups_edit_form');
        Route::post('/groups/edit/{group}.{id}', 'GroupsController@edit')->name('staff_groups_edit');

        // Warnings
        Route::get('/warnings', 'WarningController@getWarnings')->name('getWarnings');

        // Invites
        Route::get('/invites', 'InviteController@getInvites')->name('getInvites');

        // Failed Logins
        Route::get('/failedlogin', 'FailedLoginController@getFailedAttemps')->name('getFailedAttemps');

        // Polls
        Route::get('/polls', 'PollController@polls')->name('getPolls');
        Route::get('/poll/{id}', 'PollController@poll')->name('getPoll');
        Route::get('/polls/create', 'PollController@create')->name('getCreatePoll');
        Route::post('/polls/create', 'PollController@store')->name('postCreatePoll');

        // Activity Log
        Route::get('/activity', 'ActivityLogController@index')->name('activity.index');
        Route::get('/activity/{id}/delete', 'ActivityLogController@destroy')->name('activity.destroy');

        // System Gifting
        Route::get('/systemgift', 'GiftController@index')->name('systemGift');
        Route::post('/systemgift/send', 'GiftController@gift')->name('sendSystemGift');

        // MassPM
        Route::get('/masspm', 'MassPMController@massPM')->name('massPM');
        Route::post('/masspm/send', 'MassPMController@sendMassPM')->name('sendMassPM');

        // Backup Manager
        Route::get('/backup', 'BackupController@index')->name('backupManager');
        Route::post('/backup/create-full', 'BackupController@create');
        Route::post('/backup/create-files', 'BackupController@createFilesOnly');
        Route::post('/backup/create-db', 'BackupController@createDatabaseOnly');
        Route::get('/backup/download/{file_name?}', 'BackupController@download');
        Route::post('/backup/delete', 'BackupController@delete');

        // Mass Validate Users
        Route::get('/massValidateUsers', 'UserController@massValidateUsers')->name('massValidateUsers');

        // Chat Management
        Route::get('/chatManager', 'ChatController@index')->name('chatManager');
        Route::post('/chatroom/add', 'ChatController@addChatroom')->name('addChatroom');
        Route::post('/chatroom/edit/{id}', 'ChatController@editChatroom')->name('editChatroom');
        Route::post('/chatroom/delete/{id}', 'ChatController@deleteChatroom')->name('deleteChatroom');
        Route::post('/chatstatus/add', 'ChatController@addChatStatus')->name('addChatStatus');
        Route::post('/chatstatus/edit/{id}', 'ChatController@editChatStatus')->name('editChatStatus');
        Route::post('/chatstatus/delete/{id}', 'ChatController@deleteChatStatus')->name('deleteChatStatus');
        Route::get('/flushchat', 'ChatController@flushChat')->name('flush_chat');

        // Possible Cheaters
        Route::get('/cheaters', 'CheaterController@leechCheaters')->name('leechCheaters');

        // Tag (Genres)
        Route::get('/tags', 'TagController@index')->name('staff_tag_index');
        Route::get('/tag/new', 'TagController@addForm')->name('staff_tag_add_form');
        Route::post('/tag/new', 'TagController@add')->name('staff_tag_add');
        Route::get('/tag/edit/{id}', 'TagController@editForm')->name('staff_tag_edit_form');
        Route::post('/tag/edit/{id}', 'TagController@edit')->name('staff_tag_edit');

        // Applications System
        Route::get('/applications', 'ApplicationController@index')->name('staff.applications.index');
        Route::get('/applications/{id}', 'ApplicationController@show')->name('staff.applications.show');
        Route::post('/applications/{id}/approve', 'ApplicationController@approve')->name('staff.applications.approve');
        Route::post('/applications/{id}/reject', 'ApplicationController@reject')->name('staff.applications.reject');

        // Registered Seedboxes
        Route::get('/seedboxes', 'SeedboxController@index')->name('staff.seedbox.index');
        Route::delete('/seedboxes/{id}', 'SeedboxController@destroy')->name('staff.seedbox.destroy');

        // Commands
        Route::get('/commands', 'CommandController@index')->name('staff.commands.index');
        Route::get('/command/maintance-enable', 'CommandController@maintanceEnable');
        Route::get('/command/maintance-disable', 'CommandController@maintanceDisable');
        Route::get('/command/clear-cache', 'CommandController@clearCache');
        Route::get('/command/clear-view-cache', 'CommandController@clearView');
        Route::get('/command/clear-route-cache', 'CommandController@clearRoute');
        Route::get('/command/clear-config-cache', 'CommandController@clearConfig');
        Route::get('/command/clear-all-cache', 'CommandController@clearAllCache');
        Route::get('/command/set-all-cache', 'CommandController@setAllCache');
        Route::get('/command/clear-compiled', 'CommandController@clearCompiled');
        Route::get('/command/test-email', 'CommandController@testEmail');
    });
});
