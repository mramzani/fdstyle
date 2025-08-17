!function (t, e, n, i) {
    var s = "ontouchstart" in n;
    String.prototype.dot = function () {
        return "." + this
    }, String.prototype.eachHas = function (t, e, n, i) {
        var s = this, o = [];
        e = e || ", ", n = n || " ", i = i || e;
        return t.split(e).forEach(function (t, e) {
            o.push(s + n + t)
        }), o.join(i)
    }, String.prototype.padLength = function (t, e) {
        for (var n = this; n.length < e;) n = t + n;
        return n
    }, String.prototype.join = function (t, e) {
        return this + (e = "string" == typeof e ? e : " ") + t
    }, Function.prototype.setName = function (t) {
        return Object.defineProperty(this, "name", {
            get: function () {
                return t
            }
        }), this
    }, Function.prototype.clone = function () {
        var t = new Function("return " + this.toString())();
        for (var e in this) t[e] = this[e];
        return t
    };
    var o = function () {
        var t = n.createElement("div"), i = n.documentElement;
        if (!("pointerEvents" in t.style)) return !1;
        t.style.pointerEvents = "auto", t.style.pointerEvents = "x", i.appendChild(t);
        var s = e.getComputedStyle && "auto" === e.getComputedStyle(t, "").pointerEvents;
        return i.removeChild(t), !!s
    }(), a = {
        listNodeName: "ol",
        itemNodeName: "li",
        rootClass: "dd",
        listClass: "dd-list",
        itemClass: "dd-item",
        itemBlueprintClass: "dd-item-blueprint",
        dragClass: "dd-dragel",
        handleClass: "dd-handle",
        collapsedClass: "dd-collapsed",
        placeClass: "dd-placeholder",
        noDragClass: "dd-nodrag",
        emptyClass: "dd-empty",
        contentClass: "dd3-content",
        itemAddBtnClass: "item-add",
        removeBtnClass: "item-remove",
        itemRemoveBtnConfirmClass: "confirm-class",
        addBtnSelector: "",
        addBtnClass: "dd-new-item",
        editBoxClass: "dd-edit-box",
        inputSelector: "input, select, textarea",
        collapseBtnClass: "collapse",
        expandBtnClass: "expand",
        endEditBtnClass: "end-edit",
        itemBtnContainerClass: "dd-button-container",
        itemNameClass: "item-name",
        data: "",
        allowListMerging: !1,
        select2: {support: !1, selectWidth: "45%", params: {}},
        slideAnimationDuration: 0,
        maxDepth: 5,
        threshold: 15,
        refuseConfirmDelay: 2e3,
        newItemFadeIn: 350,
        event: {
            onToJson: [],
            onParseJson: [],
            onSaveEditBoxInput: [],
            onItemDrag: [],
            onItemAddChildItem: [],
            onItemDrop: [],
            onItemAdded: [],
            onItemExpanded: [],
            onItemCollapsed: [],
            onItemRemoved: [],
            onItemStartEdit: [],
            onItemEndEdit: [],
            onCreateItem: [],
            onItemMaxDepth: [],
            onItemSetParent: [],
            onItemUnsetParent: []
        },
        paramsDataKey: "__domenu_params",
        advanceEditFunction: "advanceEdit",
        advanceGroupFunction: "advanceGroup"
    };

    function r(e, i) {
        this.w = t(n), this.$instance = t(e), this.options = t.extend(!0, {}, a, i), this.init()
    }

    function d(t, e) {
        if (!t) throw new TypeError("expected object, got " + typeof t);
        this._plugin = t, this._lists = e
    }

    r.prototype = {
        init: function () {
            var n = this, i = this.options;
            n.reset(), n.$placeholder = t('<div class="' + n.options.placeClass + '"/>'), t.each(this.$instance.find(n.options.itemNodeName), function (e, i) {
                n.setParent(t(i))
            }), n.$instance.on("click", "button", function (e) {
                if (!n.$dragItem) {
                    var i = t(e.currentTarget), s = i.data("action"), o = i.parent(n.options.itemNodeName);
                    "collapse" === s && n.collapseItem(o), "expand" === s && n.expandItem(o)
                }
            });
            var o = function (e) {
                var i = t(e.target);
                if (!i.hasClass(n.options.handleClass)) {
                    if (i.closest(n.options.noDragClass.dot()).length) return;
                    i = i.closest(n.options.handleClass.dot())
                }
                i.length && !n.$dragItem && (n.isTouch = /^touch/.test(e.type), n.isTouch && 1 !== e.touches.length || (e.preventDefault(), n.dragStart(e.touches ? e.touches[0] : e)))
            }, a = function (t) {
                n.$dragItem && (t.preventDefault(), n.dragMove(t.touches ? t.touches[0] : t))
            }, r = function (t) {
                n.$dragItem && (t.preventDefault(), n.dragStop(t.touches ? t.touches[0] : t))
            };
            s && (n.$instance[0].addEventListener("touchstart", o, !1), e.addEventListener("touchmove", a, !1), e.addEventListener("touchend", r, !1), e.addEventListener("touchcancel", r, !1)), n.$instance.on("mousedown", o), n.w.on("mousemove", a), n.w.on("mouseup", r), i.addBtnSelector ? this.addNewListItemListener(t(i.addBtnSelector)) : this.addNewListItemListener(this.$instance.find(i.addBtnClass.dot()))
        }, addNewListItemListener: function (e, n) {
            var i = this, s = this.options;
            e.on("click", function (e) {
                var n = i.$instance.find(s.listClass.dot()).first(), o = i.createNewListItem();
                o && (o.css("display", "none"), n.append(o), o.fadeIn(s.newItemFadeIn), i.options.event.onItemAdded.forEach(function (n, i) {
                    n(t(o), e)
                }))
            })
        }, clickEndEditEventHandler: function (t) {
            var e = this.options;
            t.find(e.endEditBtnClass.dot()).first().on("click", null, {forced: !0}, this.keypressEnterEndEditEventHandler.bind(this))
        }, clickStartEditEventHandler: function (e) {
            var n = this.options, i = this, s = t(e.target).parents(n.itemClass.dot()).first(),
                o = s.find(n.itemNameClass.dot()).first(), a = s.find(n.inputSelector).first(),
                r = s.find(n.itemBtnContainerClass.dot()).first(), d = s.find(n.editBoxClass.dot()).first();
            !0 !== s.data("domenu_clickEndEditEventHandler") && (i.clickEndEditEventHandler(s), s.data("domenu_clickEndEditEventHandler", !0)), o.stop().hide(n.slideAnimationDuration, function () {
                "SELECT" !== a.get(0).nodeName ? a.val(o.text()) : a.val(a.find("option:contains(" + o.text() + ")").val()), r.stop().hide(n.slideAnimationDuration, function () {
                    d.stop().show(n.slideAnimationDuration, function () {
                        d.children().first("input").select().focus(), s.each(function (e, n) {
                            n = t(n);
                            var s = i.keypressEnterEndEditEventHandler;
                            n.data("domenu_keypressEnterEndEditEventHandler") || (n.data("domenu_keypressEnterEndEditEventHandler", !0), n.on("keypress", s.bind(i)))
                        })
                    })
                })
            }), n.event.onItemStartEdit.forEach(function (t, n) {
                t(s, e)
            })
        }, resolveToken: function (t, e) {
            if ("string" == typeof t) {
                for (var n = t, i = n.match(/\{\?[a-z\-\.]+\}/gi), s = i && i.length || 0, o = 0; o < s; o++) switch (i[o]) {
                    case"{?date.gregorian-slashed-DMY}":
                        var a = new Date(Date.now()),
                            r = a.getDay().toString().padLength("0", 2) + "/" + a.getMonth().toString().padLength("0", 2) + "/" + a.getFullYear();
                        n = n.replace(i[o], r);
                        break;
                    case"{?date.mysql-datetime}":
                        r = (r = new Date(Date.now())).getUTCFullYear() + "-" + ("00" + (r.getUTCMonth() + 1)).slice(-2) + "-" + ("00" + r.getUTCDate()).slice(-2) + " " + ("00" + r.getUTCHours()).slice(-2) + ":" + ("00" + r.getUTCMinutes()).slice(-2) + ":" + ("00" + r.getUTCSeconds()).slice(-2), n = n.replace(i[o], r);
                        break;
                    case"{?numeric.increment}":
                        this.incrementIncrement = this.incrementIncrement || 1, n = n.replace(i[o], this.incrementIncrement), this.incrementIncrement += 1;
                        break;
                    case"{?value}":
                        n = n.replace(i[o], e.value);
                        break;
                    default:
                        n = t
                }
                return n
            }
        }, saveEditBoxInput: function (e) {
            var n, i = this, s = this.options;
            e.each(function (e, o) {
                var a = t(o).parents(s.itemClass.dot().join(",").join(s.itemBlueprintClass.dot())).first().data(o.getAttribute("name")),
                    r = t(o).data("default-value") || "";
                if (n = t(o).parents(s.itemClass.dot()).first(), !a && !o.value) var d = i.resolveToken(r, t(o));
                n.data(o.getAttribute("name"), t(o).val() || a || d)
            }), s.event.onSaveEditBoxInput.forEach(function (t, i) {
                t(n, e)
            })
        }, keypressEnterEndEditEventHandler: function (e) {
            var n = this, i = this.options, s = t(e.target).parents(i.itemClass.dot()).first(),
                o = s.find(i.editBoxClass.dot()).first(), a = s.find(i.inputSelector), r = s.find("span").first(),
                d = s.find(i.itemBtnContainerClass.dot()).first();
            (13 === e.keyCode || e.data && e.data.forced && !0 === e.data.forced) && (n.determineAndSetItemTitle(s), "" === r.text() && r.text(n.determineAndSetItemTitle(s)), o.stop().slideUp(i.slideAnimationDuration, function () {
                n.saveEditBoxInput(a), d.attr("style", ""), i.event.onItemEndEdit.forEach(function (n, i) {
                    n(t(s), e)
                }), r.stop().slideDown(i.slideAnimationDuration)
            }))
        }, resolveInputDataEntryByName: function (e) {
            var n = this.$instance;
            opt = this.options, n.find(opt.editBoxClass.dot().join(opt.inputSelector)).each(function (n, i) {
                if (i.getAttribute("name") === e) return t(i).data("name")
            })
        }, setItemTitle: function (t, e) {
            var n = this.options;
            t.find(n.contentClass.dot().join("span")).first().text(e)
        }, determineAndSetItemTitle: function (t) {
            var e = this.options, n = t.find(e.inputSelector).first(),
                i = n.find("option:selected").first().text() || n.text(),
                s = t.find(e.editBoxClass.dot().eachHas(e.inputSelector)).first().val(),
                o = t.data(t.find(e.inputSelector).first().attr("name")),
                a = t.find(e.editBoxClass.dot().eachHas(e.inputSelector)).first().attr("placeholder"),
                r = t.find(e.editBoxClass.dot().eachHas(e.inputSelector)).first().data("placeholder"),
                d = i || s || o || this.resolveToken(r) || a;
            t.find(e.contentClass.dot().join("span")).first().text(d)
        }, setInputCollectionPlaceholders: function (e, n) {
            var i = this;
            t(n).each(function (n, s) {
                if ("SELECT" === s.nodeName) {
                    t(s).find('option[selected="selected"]').removeAttr("selected");
                    var o = t(s).find('option[value="' + e.data(t(s).attr("name")) + '"]');
                    if (0 !== o.length) o.attr("selected", "selected"); else {
                        if (!e.data(t(s).attr("name"))) return;
                        t(s).append('<option value="' + e.data(t(s).attr("name")) + '">' + e.data(t(s).attr("name")) + "</option>")
                    }
                }
                t(s).attr("placeholder", i.resolveToken(t(s).data("placeholder"), t(s)) || t(s).attr("placeholder")), t(s).val(e.data(t(s).attr("name")))
            })
        }, createNewListItem: function (n) {
            var i = this, s = this.$instance, o = this.options, a = s.find(o.itemBlueprintClass.dot()).first().clone();
            inputCollection = a.find(o.editBoxClass.dot()).find(o.inputSelector), a.remove = function () {
                var t = a.parents(i.options.itemClass.dot()).first(), e = a.data("id");
                jQuery(this).remove(), i.unsetEmptyParent(t), jQuery.each(o.event.onItemRemoved, function (t, n) {
                    n(a, e)
                })
            }, a.find(".item-edited").click(function () {
                var t = o.advanceEditFunction, n = a.data("id");
                e[t](n)
            }), a.find(".item-grouped").click(function () {
                var t = o.advanceGroupFunction, n = a.data("id");
                this.checked ? e[t](n, "checked") : e[t](n, "unchecked")
            }), a.setParameter = function (e, n) {
                a.data(o.paramsDataKey, t.extend(!0, a.data(o.paramsDataKey), {key: n}))
            }, a.getParameter = function (t) {
                return a.data(t)
            }, t.each(n || {}, function (t, e) {
                a.data(t, e)
            }), a.data("id", a.data("id") || i.getHighestId() + 1);
            var r = a.attr("class"), d = o.itemClass + r.replace(o.itemBlueprintClass, "");
            return a.attr("class", d), this.setInputCollectionPlaceholders(a, inputCollection), this.saveEditBoxInput(inputCollection), this.determineAndSetItemTitle(a), this.setInputCollectionPlaceholders(inputCollection), a.find(o.removeBtnClass.dot()).first().on("click", function (n) {
                var i = t(this), s = i.data(o.itemRemoveBtnConfirmClass);
                if (s) if (i.hasClass(s)) a.remove(); else {
                    i.addClass(s);
                    setTimeout(function () {
                        i.removeClass(s)
                    }, o.refuseConfirmDelay)
                } else if (confirm("آیا مطمن هستید ؟ حذف شاخه همراه با زیر شاخه ها غیر قابل برگشت است.")) {
                    var r = a.data("id");
                    "function" == typeof e.removeFunction ? e.removeFunction(r, a) : a.remove(), o.event.onItemRemoved.forEach(function (t, e) {
                        t(a, n)
                    })
                }
            }), a.find(o.itemAddBtnClass.dot()).first().on("click", function (t) {
                i.itemAddChildItem(a), o.event.onItemAdded.forEach(function (e, n) {
                    e(a, t)
                }), o.event.onItemAddChildItem.forEach(function (e, n) {
                    e(a, t)
                })
            }), a.find("span").first().get(0).addEventListener("click", i.clickStartEditEventHandler.bind(this)), i.options.select2.support && (a.find("select").css("width", i.options.select2.selectWidth), a.find("select").select2(i.options.select2.params)), a.data(o.paramsDataKey, a.data(o.paramsDataKey) || {}), o.event.onCreateItem.forEach(function (t, e) {
                var n = t(a, a.data());
                a = void 0 === n ? a : n
            }), a
        }, itemAddChildItem: function (e) {
            var n, i = this.options, s = this.createNewListItem();
            if (s) {
                if (e.parents(i.listClass.dot()).length > i.maxDepth) {
                    var o = e.find(i.itemAddBtnClass.dot());
                    return o.addClass(i.removeBtnClass), setTimeout(function () {
                        o.removeClass(i.removeBtnClass)
                    }, i.refuseConfirmDelay), void i.event.onItemMaxDepth.forEach(function (t, e) {
                        t()
                    })
                }
                e.children(i.listClass.dot()).length ? e.children(i.listClass.dot()).first().append(s) : ((n = t("<" + i.listNodeName + "/>").addClass(i.listClass)).append(s), e.append(n)), this.setParent(e)
            }
        }, getHighestId: function () {
            var e = this.options, n = this.$instance, i = 0;
            return n.find(e.itemNodeName).each(function (e, n) {
                var s = t(n).data("id");
                if (s > i) return i = s
            }), -1
        }, serialize: function () {
            this.options.event.onToJson.forEach(function (t, e) {
                t()
            });
            var e = this;
            return step = function (n, i) {
                var s = [];
                return n.children(e.options.itemNodeName).each(function () {
                    var n = t(this), o = n.children(e.options.listNodeName), a = {};
                    t.each(n.data(), function (t, e) {
                        0 !== t.indexOf("domenu_") && (a[t] = n.data(t))
                    });
                    var r = t.extend({}, a);
                    o.length && (r.children = step(o, i + 1)), s.push(r)
                }), s
            }, step(e.$instance.find(e.options.listNodeName).first(), 0)
        }, deserialize: function (e, n) {
            e = JSON.parse(e) || JSON.parse(this.options.data);
            var i = this, s = this.options, o = i.$instance.find(s.listClass.dot()).first();
            n && o.children().remove();
            var a = function (e, n) {
                if (e.children) {
                    var o = t('<ol class="' + s.listClass + '"></ol>');
                    if (!(r = i.createNewListItem(e))) return;
                    n.append(r), r.append(o), i.setParent(r, !0), jQuery.each(e.children, function (t, e) {
                        a(e, o)
                    })
                } else {
                    var r;
                    if (!(r = i.createNewListItem(e))) return;
                    n.append(r)
                }
            };
            jQuery.each(e, function (t, e) {
                a(e, o)
            }), i.$instance.find(i.options.itemClass.dot()).each(function (e, n) {
                t(n).data(i.options.paramsDataKey).collapsed && i.collapseItem(t(n))
            }), this.options.event.onParseJson.forEach(function (t, e) {
                t()
            })
        }, serialise: function () {
            return this.serialize()
        }, reset: function () {
            this.mouse = {
                offsetX: 0,
                offsetY: 0,
                startX: 0,
                startY: 0,
                lastX: 0,
                lastY: 0,
                nowX: 0,
                nowY: 0,
                lastCurrentDistXChange: 0,
                lastCurrentDistYChange: 0,
                isMovingOnXAxis: null,
                dirX: 0,
                dirY: 0,
                lastDirX: 0,
                lastDirY: 0,
                distAxX: 0,
                distAxY: 0,
                distXtotal: 0,
                distYtotal: 0
            }, this.isTouch = !1, this.moving = !1, this.$dragItem = null, this.dragRootEl = null, this.dragDepth = 0, this.hasNewRoot = !1, this.$pointEl = null
        }, expandItem: function (e) {
            e.removeClass(this.options.collapsedClass), e.children(this.options.listClass.dot()).children(this.options.itemClass.dot()).show(), e.children(this.options.expandBtnClass.dot()).hide(), e.children(this.options.collapseBtnClass.dot()).show(), e.data(this.options.paramsDataKey, t.extend(!0, e.data(this.options.paramsDataKey), {collapsed: !1})), this.options.event.onItemExpanded.forEach(function (t, n) {
                t(e)
            })
        }, collapseItem: function (e) {
            e.addClass(this.options.collapsedClass), e.children(this.options.listClass.dot()).children(this.options.itemClass.dot()).hide(), e.children(this.options.collapseBtnClass.dot()).hide(), e.children(this.options.expandBtnClass.dot()).show(), e.data(this.options.paramsDataKey, t.extend(!0, e.data(this.options.paramsDataKey), {collapsed: !0})), this.options.event.onItemCollapsed.forEach(function (t, n) {
                t(e)
            })
        }, expandAll: function (e) {
            var n = this, i = function (s) {
                if (e && e(s)) {
                    n.expandItem(s);
                    var o = s.children(n.options.listNodeName);
                    o.length && jQuery.each(o, function (e, n) {
                        i(t(n))
                    })
                }
            };
            n.$instance.find(this.options.collapsedClass.dot()).each(function (e, n) {
                i(t(n))
            })
        }, collapseAll: function (e) {
            var n = this, i = function (s) {
                if (e && e(s)) {
                    var o = s.children(n.options.listNodeName);
                    o.length && (n.collapseItem(s), jQuery.each(o, function (e, n) {
                        i(t(n))
                    }))
                }
            };
            n.$instance.find(n.options.listNodeName).children(n.options.itemClass.dot()).each(function (e, n) {
                i(t(n))
            })
        }, setParent: function (t, e) {
            var n = this.options;
            if (t.children(this.options.listNodeName).length || e) {
                t.children('[data-action="collapse"]').show();
                var i = t.find(this.options.handleClass.dot()).first().clone();
                t.find(this.options.handleClass.dot()).first().remove(), t.prepend(i)
            }
            t.children('[data-action="expand"]').hide(), n.event.onItemSetParent.forEach(function (e, n) {
                e(t)
            })
        }, unsetParent: function (t) {
            var e = this.options;
            t.removeClass(this.options.collapsedClass), t.children("[data-action]").hide(), t.children(this.options.listNodeName).remove(), t.removeData("children"), e.event.onItemUnsetParent.forEach(function (e, n) {
                e(t, event)
            })
        }, unsetEmptyParent: function (t) {
            0 === t.find(this.options.itemClass.dot()).length && this.unsetParent(t)
        }, getChildrenCount: function (t) {
            return t.parents(this.options.listClass.dot()).length + this.$dragItem.find(this.options.listClass.dot()).length - 1
        }, updatePlaceholderMaxDepthApperance: function () {
            this.getChildrenCount(this.$placeholder) >= this.options.maxDepth ? this.$placeholder.addClass("max-depth") : this.$placeholder.removeClass("max-depth")
        }, dragStart: function (e) {
            var i = this.mouse, s = this.options, o = t(e.target), a = o.closest(this.options.itemNodeName);
            a.attr("class").match(s.noDragClass) || (this.$placeholder.css("height", a.height()), i.offsetX = void 0 !== e.offsetX ? e.offsetX : e.pageX - o.offset().left, i.offsetY = void 0 !== e.offsetY ? e.offsetY : e.pageY - o.offset().top, i.startX = i.lastX = e.pageX, i.startY = i.lastY = e.pageY, this.dragRootEl = this.$instance, this.$dragItem = t(n.createElement(this.options.listNodeName)).addClass(this.options.listClass + " " + this.options.dragClass), this.$dragItem.css("width", a.width()), a.after(this.$placeholder), a[0].parentNode.removeChild(a[0]), a.appendTo(this.$dragItem), t(n.body).append(this.$dragItem), this.$dragItem.css({
                left: e.pageX - i.offsetX,
                top: e.pageY - i.offsetY
            }), this.updatePlaceholderMaxDepthApperance(), this.options.event.onItemDrag.forEach(function (n, i) {
                n(t(a), e)
            }))
        }, dragStop: function (e) {
            var n = this.$dragItem.children(this.options.itemNodeName).first();
            n[0].parentNode.removeChild(n[0]), this.$placeholder.replaceWith(n), this.$dragItem.remove(), this.$instance.trigger("change"), this.hasNewRoot && this.dragRootEl.trigger("change"), this.reset(), this.mouse.distXtotal = 0, this.mouse.distYtotal = 0, this.options.event.onItemDrop.forEach(function (i, s) {
                i(t(n), e)
            }), t(n).parents(this.options.rootClass).data("domenu-id") !== t(this.$instance).data("domenu-id") && t(n).parents(this.options.rootClass.dot()).domenu()._plugin.options.event.onItemDrop.forEach(function (t, i) {
                t(n, e)
            })
        }, dragMove: function (i) {
            var s, a, r = this.options, d = this.mouse;
            d.lastX = d.nowX || i.pageX, d.lastY = d.nowY || i.pageY, d.nowX = i.pageX, d.nowY = i.pageY, d.lastCurrentDistXChange = d.nowX - d.lastX, d.lastCurrentDistYChange = d.nowY - d.lastY, d.lastDirX = d.dirX, d.lastDirY = d.dirY, d.dirX = 0 === d.lastCurrentDistXChange ? 0 : d.lastCurrentDistXChange > 0 ? 1 : -1, d.dirY = 0 === d.lastCurrentDistYChange ? 0 : d.lastCurrentDistYChange > 0 ? 1 : -1;
            var l = Math.abs(this.$placeholder.offset().top - this.$dragItem.offset().top),
                h = Math.abs(this.$placeholder.offset().left - this.$dragItem.offset().left),
                p = 2 / Math.PI * Math.atan(Math.PI / 2 * (l + h) * .1 / r.threshold);
            if (this.$dragItem.css({
                left: i.pageX - d.offsetX,
                top: i.pageY - d.offsetY
            }), this.$pointEl = t(n.elementFromPoint(i.pageX - n.body.scrollLeft, i.pageY - (e.pageYOffset || n.documentElement.scrollTop))), s = this.$pointEl.closest(r.rootClass.dot()), a = this.dragRootEl.data("domenu-id") !== s.data("domenu-id"), s.length && !a ? this.$placeholder.css({opacity: 1}) : this.$placeholder.css({opacity: 1 - p}), !(0 === s.length && p > .4)) {
                var c = Math.abs(d.lastCurrentDistXChange) > Math.abs(d.lastCurrentDistYChange) && Math.abs(d.lastCurrentDistXChange - d.lastCurrentDistYChange) > 2;
                if (!1 === d.moving && (d.isMovingOnXAxis = c, d.moving = !0), I(d.nowX - d.startX, d.nowY - d.startY), d.isMovingOnXAxis = c, this.updatePlaceholderMaxDepthApperance(), 0 === this.$pointEl.parents(r.rootClass.dot()).length && I(0, 0), Math.abs(d.distXtotal) >= r.threshold) {
                    var u = this.$placeholder.prev(r.itemNodeName), f = u.find(r.listNodeName).last();
                    if (d.distXtotal > 0 && 1 === d.dirX && u.length > 0 && !u.hasClass(r.collapsedClass)) if (this.getChildrenCount(this.$placeholder) < r.maxDepth) if (f.length > 0) u.children(r.listNodeName).last().append(this.$placeholder), I(0, 0); else (C = t(n.createElement(r.listNodeName)).addClass(r.listClass)).append(this.$placeholder), u.append(C), this.setParent(u), I(0, 0); else this.getChildrenCount(this.$placeholder) >= r.maxDepth && (this.updatePlaceholderMaxDepthApperance(), r.event.onItemMaxDepth.forEach(function (t, e) {
                        t(u)
                    })); else if (d.distXtotal < 0 && -1 === d.dirX) {
                        var m = this.$placeholder.parents(r.itemClass.dot()).first();
                        m.length && (m.after(this.$placeholder), 0 === m.children(r.itemClass.dot()).length && this.unsetEmptyParent(m), I(0, 0))
                    }
                }
                if (o || (this.$dragItem[0].style.visibility = "hidden"), this.$instance.children(this.options.listClass.dot()).length || this.$instance.append(t("<" + this.options.listNodeName + "/>").attr("class", this.options.listClass)), !0 !== this.options.allowListMerging) {
                    if (a && !1 === this.options.allowListMerging) return;
                    if (a && s.data("domenu") && -1 === s.data("domenu").options.allowListMerging.indexOf(this.$instance.attr("id"))) return
                }
                if (this.$pointEl.hasClass(r.listClass) && !this.$pointEl.children(r.itemClass.dot()).length && this.$pointEl.append(this.$placeholder), this.$pointEl.parents(r.rootClass.dot()).length && !this.$pointEl.hasClass(r.listClass) && !this.$pointEl.hasClass(r.placeClass)) {
                    if (this.$pointEl.hasClass(r.itemClass) || (this.$pointEl = t(this.$pointEl).parents(r.itemClass.dot()).first()), o || (this.$dragItem[0].style.visibility = "visible"), this.$pointEl.hasClass(r.handleClass)) this.$pointEl = this.$pointEl.parent(r.itemNodeName); else if (!this.$pointEl.length || !this.$pointEl.hasClass(r.itemClass)) return;
                    if (!1 === d.isMovingOnXAxis && Math.abs(d.distYtotal) >= 5) {
                        if (a && !1 === r.allowListMerging) return;
                        if (a && "object" == typeof r.allowListMerging && -1 === r.allowListMerging.indexOf(s.attr("id"))) return;
                        I(0, 0);
                        var g = this.dragDepth - 1 + this.$pointEl.parents(r.listNodeName).length,
                            v = this.$placeholder.parent();
                        g > r.maxDepth && r.event.onItemMaxDepth.forEach(function (t, e) {
                            t(this.$dragItem)
                        });
                        var C, E = i.pageY < this.$pointEl.offset().top + this.$pointEl.height() / 2;
                        if (this.$pointEl.hasClass(r.emptyClass)) (C = t(n.createElement(r.listNodeName)).addClass(r.listClass)).append(this.$placeholder), this.$pointEl.replaceWith(C); else E && v !== this.$pointEl ? this.$pointEl.before(this.$placeholder) : d.dirY > 0 && v !== this.$pointEl && this.$pointEl.after(this.$placeholder);
                        0 === v.children().length && this.unsetEmptyParent(v.parent()), 0 === this.dragRootEl.find(r.itemNodeName).length && this.dragRootEl.append('<div class="' + r.emptyClass + '"/>'), a && (this.dragRootEl = s, this.hasNewRoot = this.$instance[0] !== this.dragRootEl[0])
                    }
                }
            }

            function I(t, e) {
                d.distXtotal = t, d.distYtotal = e, 0 == t && (d.lastX = d.startX = d.nowX), 0 == e && (d.lastY = d.startY = d.nowY)
            }
        }
    }, d.prototype = {
        getLists: function () {
            return this._lists
        }, parseJson: function (t, e) {
            t = t || null, e = e || !1;
            return this._plugin.deserialize(t, e), this
        }, toJson: function () {
            var t = this._plugin.serialize();
            return JSON.stringify(t)
        }, expandAll: function () {
            return this._plugin.expandAll(function () {
                return !0
            }), this
        }, collapseAll: function () {
            return this._plugin.collapseAll(function () {
                return !0
            }), this
        }, expand: function (t) {
            return this._plugin.expandAll(t), this
        }, collapse: function (t) {
            return this._plugin.collapseAll(t), this
        }, onParseJson: function (t) {
            return this._plugin.options.event.onParseJson.push(t.bind(this)), this
        }, onItemSetParent: function (t) {
            return this._plugin.options.event.onItemSetParent.push(t.bind(this)), this
        }, onItemUnsetParent: function (t) {
            return this._plugin.options.event.onItemUnsetParent.push(t.bind(this)), this
        }, onToJson: function (t) {
            return this._plugin.options.event.onToJson.push(t.bind(this)), this
        }, onSaveEditBoxInput: function (t) {
            return this._plugin.options.event.onSaveEditBoxInput.push(t.bind(this)), this
        }, onItemDrag: function (t) {
            return this._plugin.options.event.onItemDrag.push(t.bind(this)), this
        }, onItemDrop: function (t) {
            return this._plugin.options.event.onItemDrop.push(t.bind(this)), this
        }, onItemAdded: function (t) {
            return this._plugin.options.event.onItemAdded.push(t.bind(this)), this
        }, onItemRemoved: function (t) {
            return this._plugin.options.event.onItemRemoved.push(t.bind(this)), this
        }, onItemStartEdit: function (t) {
            return this._plugin.options.event.onItemStartEdit.push(t.bind(this)), this
        }, onItemEndEdit: function (t) {
            return this._plugin.options.event.onItemEndEdit.push(t.bind(this)), this
        }, onItemAddChildItem: function (t) {
            return this._plugin.options.event.onItemAddChildItem.push(t.bind(this)), this
        }, onCreateItem: function (t) {
            return this._plugin.options.event.onCreateItem.push(t.bind(this)), this
        }, onItemCollapsed: function (t) {
            return this._plugin.options.event.onItemCollapsed.push(t.bind(this)), this
        }, onItemExpanded: function (t) {
            return this._plugin.options.event.onItemExpanded.push(t.bind(this)), this
        }, onItemMaxDepth: function (t) {
            return this._plugin.options.event.onItemMaxDepth.push(t.bind(this)), this
        }, on: function (t, e) {
            var n = this;
            return "object" == typeof t ? t.forEach(function (t, i) {
                n._plugin.options.event[t].push(e.bind(n))
            }) : "*" === t ? Object.keys(n._plugin.options.event).forEach(function (t, i) {
                n._plugin.options.event[t].push(e.bind(n))
            }) : "string" == typeof t && n._plugin.options.event[t].push(e.bind(n)), n
        }, getListNodes: function () {
            var t = this._plugin.options;
            return this._plugin.$instance.find(t.listNodeName)
        }, getPluginOptions: function () {
            return this._plugin.options
        }
    }, t.fn.domenu = function (e) {
        var n = this.first(), i = t(this), s = i.data("domenu") || new r(this, e), o = new d(s, n);
        if (e && (s.options = t.extend(!0, {}, a, e)), i.data("domenu") && !e || i.data("domenu", s), !i.data("domenu-id")) {
            var l = Math.random().toString().replace(/\D/g, "");
            i.data("domenu-id", l)
        }
        return o || s
    }
}(window.jQuery || window.Zepto, window, document);
