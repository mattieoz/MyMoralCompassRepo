! function (a) {
    "use strict";
    var b = function (a, b) {
        this.init("tooltip", a, b)
    };
    b.prototype = {
        constructor: b,
        init: function (b, c, d) {
            var e, f;
            this.type = b, this.$element = a(c), this.options = this.getOptions(d), this.enabled = !0, this.options.trigger != "manual" && (e = this.options.trigger == "hover" ? "mouseenter" : "focus", f = this.options.trigger == "hover" ? "mouseleave" : "blur", this.$element.on(e, this.options.selector, a.proxy(this.enter, this)), this.$element.on(f, this.options.selector, a.proxy(this.leave, this))), this.options.selector ? this._options = a.extend({}, this.options, {
                trigger: "manual",
                selector: ""
            }) : this.fixTitle()
        },
        getOptions: function (b) {
            return b = a.extend({}, a.fn[this.type].defaults, b, this.$element.data()), b.delay && typeof b.delay == "number" && (b.delay = {
                show: b.delay,
                hide: b.delay
            }), b
        },
        enter: function (b) {
            var c = a(b.currentTarget)[this.type](this._options).data(this.type);
            if (!c.options.delay || !c.options.delay.show) return c.show();
            clearTimeout(this.timeout), c.hoverState = "in", this.timeout = setTimeout(function () {
                c.hoverState == "in" && c.show()
            }, c.options.delay.show)
        },
        leave: function (b) {
            var c = a(b.currentTarget)[this.type](this._options).data(this.type);
            this.timeout && clearTimeout(this.timeout);
            if (!c.options.delay || !c.options.delay.hide) return c.hide();
            c.hoverState = "out", this.timeout = setTimeout(function () {
                c.hoverState == "out" && c.hide()
            }, c.options.delay.hide)
        },
        show: function () {
            var a, b, c, d, e, f, g;
            if (this.hasContent() && this.enabled) {
                a = this.tip(), this.setContent(), this.options.animation && a.addClass("fade"), f = typeof this.options.placement == "function" ? this.options.placement.call(this, a[0], this.$element[0]) : this.options.placement, b = /in/.test(f), a.remove().css({
                    top: 0,
                    left: 0,
                    display: "block"
                }).appendTo(b ? this.$element : document.body), c = this.getPosition(b), d = a[0].offsetWidth, e = a[0].offsetHeight;
                switch (b ? f.split(" ")[1] : f) {
                case "bottom":
                    g = {
                        top: c.top + c.height,
                        left: c.left + c.width / 2 - d / 2
                    };
                    break;
                case "top":
                    g = {
                        top: c.top - e,
                        left: c.left + c.width / 2 - d / 2
                    };
                    break;
                case "left":
                    g = {
                        top: c.top + c.height / 2 - e / 2,
                        left: c.left - d
                    };
                    break;
                case "right":
                    g = {
                        top: c.top + c.height / 2 - e / 2,
                        left: c.left + c.width
                    }
                }
                a.css(g).addClass(f).addClass("in")
            }
        },
        isHTML: function (a) {
            return typeof a != "string" || a.charAt(0) === "<" && a.charAt(a.length - 1) === ">" && a.length >= 3 || /^(?:[^<]*<[\w\W]+>[^>]*$)/.exec(a)
        },
        setContent: function () {
            var a = this.tip(),
                b = this.getTitle();
            a.find(".tooltip-inner")[this.isHTML(b) ? "html" : "text"](b), a.removeClass("fade in top bottom left right")
        },
        hide: function () {
            function d() {
                var b = setTimeout(function () {
                    c.off(a.support.transition.end).remove()
                }, 500);
                c.one(a.support.transition.end, function () {
                    clearTimeout(b), c.remove()
                })
            }
            var b = this,
                c = this.tip();
            c.removeClass("in"), a.support.transition && this.$tip.hasClass("fade") ? d() : c.remove()
        },
        fixTitle: function () {
            var a = this.$element;
            (a.attr("title") || typeof a.attr("data-original-title") != "string") && a.attr("data-original-title", a.attr("title") || "").removeAttr("title")
        },
        hasContent: function () {
            return this.getTitle()
        },
        getPosition: function (b) {
            return a.extend({}, b ? {
                top: 0,
                left: 0
            } : this.$element.offset(), {
                width: this.$element[0].offsetWidth,
                height: this.$element[0].offsetHeight
            })
        },
        getTitle: function () {
            var a, b = this.$element,
                c = this.options;
            return a = b.attr("data-original-title") || (typeof c.title == "function" ? c.title.call(b[0]) : c.title), a
        },
        tip: function () {
            return this.$tip = this.$tip || a(this.options.template)
        },
        validate: function () {
            this.$element[0].parentNode || (this.hide(), this.$element = null, this.options = null)
        },
        enable: function () {
            this.enabled = !0
        },
        disable: function () {
            this.enabled = !1
        },
        toggleEnabled: function () {
            this.enabled = !this.enabled
        },
        toggle: function () {
            this[this.tip().hasClass("in") ? "hide" : "show"]()
        }
    }, a.fn.tooltip = function (c) {
        return this.each(function () {
            var d = a(this),
                e = d.data("tooltip"),
                f = typeof c == "object" && c;
            e || d.data("tooltip", e = new b(this, f)), typeof c == "string" && e[c]()
        })
    }, a.fn.tooltip.Constructor = b, a.fn.tooltip.defaults = {
        animation: !0,
        placement: "top",
        selector: !1,
        template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
        trigger: "hover",
        title: "",
        delay: 0
    }
}(window.jQuery), ! function (a) {
    "use strict";
    var b = function (a, b) {
        this.init("popover", a, b)
    };
    b.prototype = a.extend({}, a.fn.tooltip.Constructor.prototype, {
        constructor: b,
        setContent: function () {
            var a = this.tip(),
                b = this.getTitle(),
                c = this.getContent();
            a.find(".popover-title")[this.isHTML(b) ? "html" : "text"](b), a.find(".popover-content > *")[this.isHTML(c) ? "html" : "text"](c), a.removeClass("fade top bottom left right in")
        },
        hasContent: function () {
            return this.getTitle() || this.getContent()
        },
        getContent: function () {
            var a, b = this.$element,
                c = this.options;
            return a = b.attr("data-content") || (typeof c.content == "function" ? c.content.call(b[0]) : c.content), a
        },
        tip: function () {
            return this.$tip || (this.$tip = a(this.options.template)), this.$tip
        }
    }), a.fn.popover = function (c) {
        return this.each(function () {
            var d = a(this),
                e = d.data("popover"),
                f = typeof c == "object" && c;
            e || d.data("popover", e = new b(this, f)), typeof c == "string" && e[c]()
        })
    }, a.fn.popover.Constructor = b, a.fn.popover.defaults = a.extend({}, a.fn.tooltip.defaults, {
        placement: "right",
        content: "",
        template: '<div class="popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>'
    })
}(window.jQuery),
function (a) {
    var b = function () {
        return {
            tmp: [],
            hidden: null,
            adjust: function (b) {
                var c = this;
                c.hidden = b.parents().andSelf().filter(":hidden"), c.hidden.each(function () {
                    var b = a(this);
                    c.tmp.push(b.attr("style")), b.css({
                        visibility: "hidden",
                        display: "block"
                    })
                })
            },
            reset: function () {
                var b = this;
                b.hidden.each(function (c) {
                    var d = a(this),
                        e = b.tmp[c];
                    e === undefined ? d.removeAttr("style") : d.attr("style", e)
                }), b.tmp = [], b.hidden = null
            }
        }
    };
    jQuery.foundation = jQuery.foundation || {}, jQuery.foundation.customForms = jQuery.foundation.customForms || {}, a.foundation.customForms.appendCustomMarkup = function (c) {
        function e(b, c) {
            var d = a(c).hide(),
                e = d.attr("type"),
                f = d.next("span.custom." + e);
            f.length === 0 && (f = a('<span class="custom ' + e + '"></span>').insertAfter(d)), f.toggleClass("checked", d.is(":checked")), f.toggleClass("disabled", d.is(":disabled"))
        }
        function f(c, d) {
            var e = b(),
                f = a(d),
                g = f.next("div.custom.dropdown"),
                h = g.find("ul"),
                i = g.find(".current"),
                j = g.find(".selector"),
                k = f.find("option"),
                l = k.filter(":selected"),
                m = 0,
                n = "",
                o, p = !1;
            if (f.hasClass("no-custom")) return;
            if (g.length === 0) {
                var q = f.hasClass("small") ? "small" : f.hasClass("medium") ? "medium" : f.hasClass("large") ? "large" : f.hasClass("expand") ? "expand" : "";
                g = a('<div class="' + ["custom", "dropdown", q].join(" ") + '"><a href="#" class="selector"></a><ul /></div>"'), j = g.find(".selector"), h = g.find("ul"), n = k.map(function () {
                    return "<li>" + a(this).html() + "</li>"
                }).get().join(""), h.append(n), p = g.prepend('<a href="#" class="current">' + l.html() + "</a>").find(".current"), f.after(g).hide()
            } else n = k.map(function () {
                return "<li>" + a(this).html() + "</li>"
            }).get().join(""), h.html("").append(n);
            g.toggleClass("disabled", f.is(":disabled")), o = h.find("li"), k.each(function (b) {
                this.selected && (o.eq(b).addClass("selected"), p && p.html(a(this).html()))
            }), h.css("width", "inherit"), g.css("width", "inherit"), g.is(".small, .medium, .large, .expand") || (g.addClass("open"), e.adjust(h), m = o.outerWidth() > m ? o.outerWidth() : m, e.reset(), g.removeClass("open"), g.width(m + 18), h.width(m + 16))
        }
        var d = {
            disable_class: "js-disable-custom"
        };
        c = a.extend(d, c), a("form.custom input:radio[data-customforms!=disabled]").each(e), a("form.custom input:checkbox[data-customforms!=disabled]").each(e), a("form.custom select[data-customforms!=disabled]").each(f)
    };
    var c = function (b) {
        var c = 0,
            d = b.next();
        $options = b.find("option"), d.find("ul").html(""), $options.each(function () {
            $li = a("<li>" + a(this).html() + "</li>"), d.find("ul").append($li)
        }), $options.each(function (b) {
            this.selected && (d.find("li").eq(b).addClass("selected"), d.find(".current").html(a(this).html()))
        }), d.removeAttr("style").find("ul").removeAttr("style"), d.find("li").each(function () {
            d.addClass("open"), a(this).outerWidth() > c && (c = a(this).outerWidth()), d.removeClass("open")
        }), d.css("width", c + 18 + "px"), d.find("ul").css("width", c + 16 + "px")
    }, d = function (a) {
        var b = a.prev(),
            c = b[0];
        !1 === b.is(":disabled") && (c.checked = c.checked ? !1 : !0, a.toggleClass("checked"))
    }, e = function (b) {
        var c = b.prev(),
            d = c[0];
        !1 === c.is(":disabled") && (a('input:radio[name="' + c.attr("name") + '"]').each(function () {
            a(this).next().removeClass("checked")
        }), d.checked = d.checked ? !1 : !0, b.toggleClass("checked"), c.trigger("change"))
    };
    a(document).on("click", "form.custom span.custom.checkbox", function (b) {
        b.preventDefault(), b.stopPropagation(), d(a(this))
    }), a(document).on("click", "form.custom span.custom.radio", function (b) {
        b.preventDefault(), b.stopPropagation(), e(a(this))
    }), a(document).on("change", "form.custom select[data-customforms!=disabled]", function (a) {}), a(document).on("click", "form.custom label", function (b) {
        var c = a("#" + a(this).attr("for")),
            f, g;
        c.length !== 0 && (c.attr("type") === "checkbox" ? (b.preventDefault(), f = a(this).find("span.custom.checkbox"), d(f)) : c.attr("type") === "radio" && (b.preventDefault(), g = a(this).find("span.custom.radio"), e(g)))
    }), a(document).on("click", "form.custom div.custom.dropdown a.current, form.custom div.custom.dropdown a.selector", function (b) {
        var c = a(this),
            d = c.closest("div.custom.dropdown"),
            e = d.prev();
        b.preventDefault(), a("div.dropdown").removeClass("open");
        if (!1 === e.is(":disabled")) return d.toggleClass("open"), d.hasClass("open") ? a(document).bind("click.customdropdown", function (b) {
            d.removeClass("open"), a(document).unbind(".customdropdown")
        }) : a(document).unbind(".customdropdown"), !1
    }), a(document).on("click", "form.custom div.custom.dropdown li", function (b) {
        var c = a(this),
            d = c.closest("div.custom.dropdown"),
            e = d.prev(),
            f = 0;
        b.preventDefault(), b.stopPropagation(), a("div.dropdown").removeClass("open"), c.closest("ul").find("li").removeClass("selected"), c.addClass("selected"), d.removeClass("open").find("a.current").html(c.html()), c.closest("ul").find("li").each(function (a) {
            c[0] == this && (f = a)
        }), e[0].selectedIndex = f, e.trigger("change")
    }), a.fn.foundationCustomForms = a.foundation.customForms.appendCustomMarkup
}(jQuery),
function (a) {
    function b(a) {
        var b = a.attr("placeholder");
        c(a, b), a.focus(function (c) {
            if (a.data("changed") === !0) return;
            a.val() === b && a.val("")
        }).blur(function (c) {
            a.val() === "" && a.val(b)
        }).change(function (b) {
            a.data("changed", a.val() !== "")
        })
    }
    function c(a, b) {
        a.val() === "" ? a.val(b) : a.data("changed", !0)
    }
    function d(b) {
        var c = e(b);
        b.after(c), b.val() === "" ? b.hide() : c.hide(), a(b).blur(function (a) {
            if (b.val() !== "") return;
            b.hide(), c.show()
        }), a(c).focus(function (a) {
            b.show().focus(), c.hide()
        })
    }
    function e(b) {
        return a("<input>").attr({
            placeholder: b.attr("placeholder"),
            value: b.attr("placeholder"),
            id: b.attr("id"),
            readonly: !0
        }).addClass(b.attr("class"))
    }
    function f(b) {
        b.find(":input[placeholder]").each(function () {
            if (a(this).data("changed") === !0) return;
            a(this).val() === a(this).attr("placeholder") && a(this).val("")
        })
    }
    if ("placeholder" in document.createElement("input")) return;
    a(document).ready(function () {
        a(":input[placeholder]").not(":password").each(function () {
            b(a(this))
        }), a(":password[placeholder]").each(function () {
            d(a(this))
        }), a("form").submit(function (b) {
            f(a(this))
        })
    })
}(jQuery),
function (a) {
    a(document).foundationCustomForms(), a("#expand-scoreboard").on("click", function (b) {
        b.preventDefault();
        var c = a(this).closest(".your-scorecard");
        c.find(a(this)).remove(), c.hide().appendTo("#your-score-area").slideDown("slow")
    }), a(".score-form select").on("change", function () {
        var b = a(this).val().toLowerCase().replace(/[^a-z]/g, ""),
            c = a(this).next(".dropdown").find(".current");
        c.attr("class", "current"), c.addClass(b)
    }), a(".dropdown li").each(function () {
        a(this).addClass(a(this).text().toLowerCase().replace(/[^a-z]/g, ""))
    }), a.fn.persistantPopover = function () {
        function c() {
            b = setTimeout(function () {
                a(".popover").hide()
            }, 200)
        }
        var b;
        return this.each(function () {
            var b = a(this);
            b.popover({
                trigger: "manual",
                placement: "right",
                animation: !1,
                content: b.data("excerpt") + '<a class="more-link" href="' + b.attr("href") + '">Read more and hand out grades</a>'
            })
        }).mouseenter(function () {
            clearTimeout(b), a(".popover").remove(), a(this).popover("show")
        }).mouseleave(function () {
            c(), a(".popover").mouseenter(function () {
                clearTimeout(b)
            }).mouseleave(function () {
                c()
            })
        })
    }, a(".group-list a").persistantPopover()
}(jQuery);