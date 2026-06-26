/**
 * Client Media Vault — Admin JS
 *
 * 전략 (Strategy):
 * 1. Render Select2 on our custom fields.
 * 2. On the classic attachment edit page, add a hidden nonce so edit_attachment
 *    can verify and save from $_POST.
 * 3. In the media modal, intercept the "Save" button click and fire our own
 *    AJAX (ca_save_attachment_meta) BEFORE WordPress's save-attachment-compat.
 */

(function ($) {
    'use strict';

    var AJAX_URL = (typeof ca_Admin !== 'undefined') ? ca_Admin.ajax_url : ajaxurl;

    /* ════════════════════════════════════════════════════════
       Select2 init — idempotent, scoped to a context element
       ════════════════════════════════════════════════════════ */
    function initSelect2($ctx) {
        if (!$ctx || !$ctx.length) {
            $ctx = $(document);
        }

        $ctx.find('.ca-s2-users').each(function () {
            var $el = $(this);
            if ($el.hasClass('select2-hidden-accessible')) {
                try {
                    $el.select2('destroy');
                } catch (e) { }
            }
            var $drop = $el.closest('.media-modal, .attachment-details-two-column, table');
            $el.select2({
                placeholder: 'Search and select clients…',
                allowClear: true,
                width: '100%',
                dropdownParent: $drop.length ? $drop : $('body')
            });
        });

        $ctx.find('.ca-s2-cats').each(function () {
            var $el = $(this);
            if ($el.hasClass('select2-hidden-accessible')) {
                try {
                    $el.select2('destroy');
                } catch (e) { }
            }
            var $drop = $el.closest('.media-modal, .attachment-details-two-column, table');
            $el.select2({
                placeholder: 'Search and select categories…',
                allowClear: true,
                width: '100%',
                dropdownParent: $drop.length ? $drop : $('body')
            });
        });
    }

    /* ════════════════════════════════════════════════════════
       Collect values for a given post ID from Select2 widgets
       ════════════════════════════════════════════════════════ */
    function collectValues(postId) {
        var $usersEl = $('#ca_users_' + postId);
        var $catsEl = $('#ca_cats_' + postId);
        var nonce = $usersEl.data('nonce') || $catsEl.data('nonce') || '';
        return {
            nonce: nonce,
            post_id: postId,
            users: $usersEl.val() || [],
            cats: $catsEl.val() || []
        };
    }

    /* ════════════════════════════════════════════════════════
       Fire our AJAX save, return a jQuery Deferred
       ════════════════════════════════════════════════════════ */
    function caSave(postId) {
        var data = collectValues(postId);

        // If no nonce found, nothing to save (fields not rendered)
        if (!data.nonce) {
            return $.Deferred().resolve().promise();
        }

        return $.post(AJAX_URL, {
            action: 'ca_save_attachment_meta',
            nonce: data.nonce,
            post_id: data.post_id,
            users: data.users,
            cats: data.cats
        });
    }

    /* ════════════════════════════════════════════════════════
       1. Classic attachment edit page (/wp-admin/post.php)
       ════════════════════════════════════════════════════════ */
    $(function () {
        initSelect2($(document));

        $('form#post').on('submit.ca', function (e) {
            var $form = $(this);
            var postId = parseInt($('#post_ID').val(), 10);
            if (!postId) {
                return;
            }

            if (!$('#ca_users_' + postId).length && !$('#ca_cats_' + postId).length) {
                return;
            }

            e.preventDefault();
            caSave(postId).always(function () {
                $form.off('submit.ca').submit(); // resubmit
            });
        });
    });

    /* ════════════════════════════════════════════════════════
       2. Media modal (wp.media Backbone)
       ════════════════════════════════════════════════════════ */
    $(function () {
        if (typeof wp === 'undefined' || !wp.media) {
            return;
        }

        var MO = window.MutationObserver || window.WebKitMutationObserver;
        var _debTimer = null;

        function onModalMutation(mutations) {
            for (var i = 0; i < mutations.length; i++) {
                var nodes = mutations[i].addedNodes;
                for (var j = 0; j < nodes.length; j++) {
                    var node = nodes[j];
                    if (node.nodeType !== 1) {
                        continue;
                    }
                    var $n = $(node);
                    if ($n.find('.ca-s2-users, .ca-s2-cats').length ||
                        $n.hasClass('compat-attachment-fields') ||
                        $n.hasClass('attachment-details')) {
                        clearTimeout(_debTimer);
                        _debTimer = setTimeout(function () {
                            var $modal = $('.media-modal');
                            initSelect2($modal.length ? $modal : $(document));
                        }, 150);
                    }
                }
            }
        }

        if (MO) {
            var obs = new MO(onModalMutation);
            obs.observe(document.getElementById('wpbody') || document.body, {
                childList: true, subtree: true
            });
        }

        $(document).on('click.ca', '.attachment', function () {
            clearTimeout(_debTimer);
            _debTimer = setTimeout(function () {
                initSelect2($('.media-modal'));
            }, 300);
        });

        /* ── Intercept the modal "Save" button ─────────────── */
        $(document).on('click.ca', '.media-modal .save-attachment, .attachment-details .save', function (e) {
            var $btn = $(this);
            var $modal = $btn.closest('.media-modal');
            var postId = 0;

            var $usersEl = $modal.find('.ca-s2-users');
            if ($usersEl.length) {
                postId = parseInt($usersEl.data('post-id'), 10);
            }
            if (!postId) {
                var $catsEl = $modal.find('.ca-s2-cats');
                if ($catsEl.length) {
                    postId = parseInt($catsEl.data('post-id'), 10);
                }
            }
            if (!postId) {
                return;
            }

            e.stopImmediatePropagation();
            e.preventDefault();

            $btn.prop('disabled', true).text('Saving…');

            caSave(postId).always(function () {
                $btn.prop('disabled', false).text('Save');
                $(document).off('click.ca', '.media-modal .save-attachment, .attachment-details .save');
                $btn[0].click();
                setTimeout(function () {
                    attachModalSaveHook();
                }, 400);
            });
        });

        function attachModalSaveHook() {
            $(document).on('click.ca', '.media-modal .save-attachment, .attachment-details .save', function (e) {
                var $btn = $(this);
                var $modal = $btn.closest('.media-modal');
                var postId = 0;
                var $uel = $modal.find('.ca-s2-users');
                if ($uel.length) {
                    postId = parseInt($uel.data('post-id'), 10);
                }
                if (!postId) {
                    var $cel = $modal.find('.ca-s2-cats');
                    if ($cel.length) {
                        postId = parseInt($cel.data('post-id'), 10);
                    }
                }
                if (!postId) {
                    return;
                }

                e.stopImmediatePropagation();
                e.preventDefault();
                $btn.prop('disabled', true).text('Saving…');
                caSave(postId).always(function () {
                    $btn.prop('disabled', false).text('Save');
                    $(document).off('click.ca', '.media-modal .save-attachment, .attachment-details .save');
                    $btn[0].click();
                    setTimeout(attachModalSaveHook, 400);
                });
            });
        }
    });

})(jQuery);
