(window.webpackWcBlocksJsonp = window.webpackWcBlocksJsonp || []).push([
    [11, 75],
    {
        113: function (e, t, r) {
            "use strict";
            r.d(t, "a", function () {
                return c;
            }),
                r.d(t, "b", function () {
                    return o;
                });
            var n = r(45);
            const c = function () {
                    let e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : [],
                        t = arguments.length > 1 ? arguments[1] : void 0,
                        r = arguments.length > 2 ? arguments[2] : void 0,
                        c = arguments.length > 3 && void 0 !== arguments[3] ? arguments[3] : "";
                    const o = e.filter((e) => e.attribute === r.taxonomy),
                        i = o.length ? o[0] : null;
                    if (!(i && i.slug && Array.isArray(i.slug) && i.slug.includes(c))) return;
                    const a = i.slug.filter((e) => e !== c),
                        l = e.filter((e) => e.attribute !== r.taxonomy);
                    a.length > 0 && ((i.slug = a.sort()), l.push(i)), t(Object(n.a)(l).asc("attribute"));
                },
                o = function () {
                    let e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : [],
                        t = arguments.length > 1 ? arguments[1] : void 0,
                        r = arguments.length > 2 ? arguments[2] : void 0,
                        c = arguments.length > 3 && void 0 !== arguments[3] ? arguments[3] : [],
                        o = arguments.length > 4 && void 0 !== arguments[4] ? arguments[4] : "in";
                    if (!r || !r.taxonomy) return [];
                    const i = e.filter((e) => e.attribute !== r.taxonomy);
                    return (
                        0 === c.length
                            ? t(i)
                            : (i.push({
                                  attribute: r.taxonomy,
                                  operator: o,
                                  slug: c
                                      .map((e) => {
                                          let { slug: t } = e;
                                          return t;
                                      })
                                      .sort(),
                              }),
                              t(Object(n.a)(i).asc("attribute"))),
                        i
                    );
                };
        },
        120: function (e, t, r) {
            "use strict";
            r.d(t, "a", function () {
                return o;
            }),
                r.d(t, "b", function () {
                    return i;
                });
            var n = r(2);
            r(141), r(4);
            const c = Object(n.getSetting)("attributes", []).reduce((e, t) => {
                    const r = (n = t) && n.attribute_name ? { id: parseInt(n.attribute_id, 10), name: n.attribute_name, taxonomy: "pa_" + n.attribute_name, label: n.attribute_label } : null;
                    var n;
                    return r && r.id && e.push(r), e;
                }, []),
                o = (e) => {
                    if (e) return c.find((t) => t.id === e);
                },
                i = (e) => {
                    if (e) return c.find((t) => t.taxonomy === e);
                };
        },
        140: function (e) {
            e.exports = JSON.parse(
                '{"name":"woocommerce/active-filters","version":"1.0.0","title":"Active Filters Controls","description":"Display the currently active filters.","category":"woocommerce","keywords":["WooCommerce"],"supports":{"html":false,"multiple":false,"inserter":false,"color":{"text":true,"background":false},"lock":false},"attributes":{"displayStyle":{"type":"string","default":"list"},"headingLevel":{"type":"number","default":3}},"textdomain":"woocommerce","apiVersion":2,"$schema":"https://schemas.wp.org/trunk/block.json"}'
            );
        },
        141: function (e, t, r) {
            "use strict";
            r.d(t, "b", function () {
                return c;
            }),
                r.d(t, "c", function () {
                    return o;
                }),
                r.d(t, "a", function () {
                    return a;
                });
            var n = r(21);
            const c = (e) =>
                    Object(n.b)(e, "count") &&
                    Object(n.b)(e, "description") &&
                    Object(n.b)(e, "id") &&
                    Object(n.b)(e, "name") &&
                    Object(n.b)(e, "parent") &&
                    Object(n.b)(e, "slug") &&
                    "number" == typeof e.count &&
                    "string" == typeof e.description &&
                    "number" == typeof e.id &&
                    "string" == typeof e.name &&
                    "number" == typeof e.parent &&
                    "string" == typeof e.slug,
                o = (e) => Array.isArray(e) && e.every(c),
                i = (e) =>
                    Object(n.b)(e, "attribute") &&
                    Object(n.b)(e, "operator") &&
                    Object(n.b)(e, "slug") &&
                    "string" == typeof e.attribute &&
                    "string" == typeof e.operator &&
                    Array.isArray(e.slug) &&
                    e.slug.every((e) => "string" == typeof e),
                a = (e) => Array.isArray(e) && e.every(i);
        },
        160: function (e, t, r) {
            "use strict";
            var n = r(0),
                c = r(1),
                o = r(33),
                i = r(2),
                a = r(5),
                l = r.n(a),
                s = r(20),
                u = r(65),
                b = r(21),
                f = r(141),
                d = r(69),
                p = r(62);
            r(227);
            var m = r(120),
                O = r(44),
                g = r(56),
                j = r(22),
                y = r(113),
                v = (e) => {
                    let { attributeObject: t, slugs: r = [], operator: a = "in", displayStyle: l, isLoadingCallback: s } = e;
                    const { results: b, isLoading: d } = Object(g.a)({ namespace: "/wc/store/v1", resourceName: "products/attributes/terms", resourceValues: [t.id] }),
                        [p, m] = Object(o.b)("attributes", []);
                    if (
                        (Object(n.useEffect)(() => {
                            s(d);
                        }, [d, s]),
                        !Array.isArray(b) || !Object(f.c)(b) || !Object(f.a)(p))
                    )
                        return null;
                    const v = t.label,
                        h = Object(i.getSettingWithCoercion)("is_rendering_php_template", !1, u.a);
                    return Object(n.createElement)(
                        "li",
                        null,
                        Object(n.createElement)("span", { className: "wc-block-active-filters__list-item-type" }, v, ":"),
                        Object(n.createElement)(
                            "ul",
                            null,
                            r.map((e, r) => {
                                const o = b.find((t) => t.slug === e);
                                if (!o) return null;
                                let i = "";
                                return (
                                    r > 0 && "and" === a && (i = Object(n.createElement)("span", { className: "wc-block-active-filters__list-item-operator" }, Object(c.__)("All", "woocommerce"))),
                                    Object(O.f)({
                                        type: v,
                                        name: Object(j.decodeEntities)(o.name || e),
                                        prefix: i,
                                        isLoading: d,
                                        removeCallback: () => {
                                            const r = p.find((e) => {
                                                let { attribute: r } = e;
                                                return r === "pa_" + t.name;
                                            });
                                            1 === (null == r ? void 0 : r.slug.length) ? Object(O.e)("query_type_" + t.name, "filter_" + t.name) : Object(O.e)({ ["filter_" + t.name]: e }), h || Object(y.a)(p, m, t, e);
                                        },
                                        showLabel: !1,
                                        displayStyle: l,
                                    })
                                );
                            })
                        )
                    );
                },
                h = (e) => {
                    let { displayStyle: t, isLoading: r } = e;
                    return r
                        ? Object(n.createElement)(
                              n.Fragment,
                              null,
                              [...Array("list" === t ? 2 : 3)].map((e, r) =>
                                  Object(n.createElement)("li", { className: "list" === t ? "show-loading-state-list" : "show-loading-state-chips", key: r }, Object(n.createElement)("span", { className: "show-loading-state__inner" }))
                              )
                          )
                        : null;
                },
                _ = r(47);
            t.a = (e) => {
                let { attributes: t, isEditor: r = !1 } = e;
                const a = Object(_.b)(),
                    g = (function () {
                        const e = Object(n.useRef)(!1);
                        return (
                            Object(n.useEffect)(
                                () => (
                                    (e.current = !0),
                                    () => {
                                        e.current = !1;
                                    }
                                ),
                                []
                            ),
                            Object(n.useCallback)(() => e.current, [])
                        );
                    })()(),
                    j = Object(i.getSettingWithCoercion)("is_rendering_php_template", !1, u.a),
                    [y, w] = Object(n.useState)(!0),
                    k = Object(O.c)() && !r && y,
                    [E, S] = Object(o.b)("attributes", []),
                    [A, N] = Object(o.b)("stock_status", []),
                    [x, C] = Object(o.b)("min_price"),
                    [R, L] = Object(o.b)("max_price"),
                    [F, T] = Object(o.b)("rating"),
                    Q = Object(i.getSetting)("stockStatusOptions", []),
                    z = Object(i.getSetting)("attributes", []),
                    P = Object(n.useMemo)(() => {
                        if (
                            k ||
                            0 === A.length ||
                            ((e = A), !Array.isArray(e) || !e.every((e) => ["instock", "outofstock", "onbackorder"].includes(e))) ||
                            !((e) => Object(b.a)(e) && Object.keys(e).every((e) => ["instock", "outofstock", "onbackorder"].includes(e)))(Q)
                        )
                            return null;
                        var e;
                        const r = Object(c.__)("Stock Status", "woocommerce");
                        return Object(n.createElement)(
                            "li",
                            null,
                            Object(n.createElement)("span", { className: "wc-block-active-filters__list-item-type" }, r, ":"),
                            Object(n.createElement)(
                                "ul",
                                null,
                                A.map((e) =>
                                    Object(O.f)({
                                        type: r,
                                        name: Q[e],
                                        removeCallback: () => {
                                            if ((Object(O.e)({ filter_stock_status: e }), !j)) {
                                                const t = A.filter((t) => t !== e);
                                                N(t);
                                            }
                                        },
                                        showLabel: !1,
                                        displayStyle: t.displayStyle,
                                    })
                                )
                            )
                        );
                    }, [k, Q, A, N, t.displayStyle, j]),
                    B = Object(n.useMemo)(
                        () =>
                            k || (!Number.isFinite(x) && !Number.isFinite(R))
                                ? null
                                : Object(O.f)({
                                      type: Object(c.__)("Price", "woocommerce"),
                                      name: Object(O.b)(x, R),
                                      removeCallback: () => {
                                          Object(O.e)("max_price", "min_price"), j || (C(void 0), L(void 0));
                                      },
                                      displayStyle: t.displayStyle,
                                  }),
                        [k, x, R, t.displayStyle, C, L, j]
                    ),
                    $ = Object(n.useMemo)(
                        () =>
                            (!Object(f.a)(E) && g) || (!E.length && !Object(O.g)(z))
                                ? (y && w(!1), null)
                                : E.map((e) => {
                                      const r = Object(m.b)(e.attribute);
                                      return r ? Object(n.createElement)(v, { attributeObject: r, displayStyle: t.displayStyle, slugs: e.slug, key: e.attribute, operator: e.operator, isLoadingCallback: w }) : (y && w(!1), null);
                                  }),
                        [E, g, z, y, t.displayStyle]
                    );
                Object(n.useEffect)(() => {
                    var e;
                    if (!j) return;
                    if (F.length && F.length > 0) return;
                    const t = null === (e = Object(d.d)("rating_filter")) || void 0 === e ? void 0 : e.toString();
                    t && T(t.split(","));
                }, [j, F, T]);
                const W = Object(n.useMemo)(() => {
                    if (k || 0 === F.length || ((e = F), !Array.isArray(e) || !e.every((e) => ["1", "2", "3", "4", "5"].includes(e)))) return null;
                    var e;
                    const r = Object(c.__)("Rating", "woocommerce");
                    return Object(n.createElement)(
                        "li",
                        null,
                        Object(n.createElement)("span", { className: "wc-block-active-filters__list-item-type" }, r, ":"),
                        Object(n.createElement)(
                            "ul",
                            null,
                            F.map((e) =>
                                Object(O.f)({
                                    type: r,
                                    name: Object(c.sprintf)(
                                        /* translators: %s is referring to the average rating value */
                                        Object(c.__)("Rated %s out of 5", "woocommerce"),
                                        e
                                    ),
                                    removeCallback: () => {
                                        if ((Object(O.e)({ rating_filter: e }), !j)) {
                                            const t = F.filter((t) => t !== e);
                                            T(t);
                                        }
                                    },
                                    showLabel: !1,
                                    displayStyle: t.displayStyle,
                                })
                            )
                        )
                    );
                }, [k, F, T, t.displayStyle, j]);
                if (!k && !(E.length > 0 || A.length > 0 || F.length > 0 || Number.isFinite(x) || Number.isFinite(R)) && !r) return a(!1), null;
                const Y = "h" + t.headingLevel,
                    V = Object(n.createElement)(Y, { className: "wc-block-active-filters__title" }, t.heading),
                    D = k ? Object(n.createElement)(p.a, null, V) : V;
                if (!Object(i.getSettingWithCoercion)("has_filterable_products", !1, u.a)) return a(!1), null;
                a(!0);
                const K = l()("wc-block-active-filters__list", { "wc-block-active-filters__list--chips": "chips" === t.displayStyle, "wc-block-active-filters--loading": k });
                return Object(n.createElement)(
                    n.Fragment,
                    null,
                    !r && t.heading && D,
                    Object(n.createElement)(
                        "div",
                        { className: "wc-block-active-filters" },
                        Object(n.createElement)(
                            "ul",
                            { className: K },
                            r
                                ? Object(n.createElement)(
                                      n.Fragment,
                                      null,
                                      Object(O.f)({ type: Object(c.__)("Size", "woocommerce"), name: Object(c.__)("Small", "woocommerce"), displayStyle: t.displayStyle }),
                                      Object(O.f)({ type: Object(c.__)("Color", "woocommerce"), name: Object(c.__)("Blue", "woocommerce"), displayStyle: t.displayStyle })
                                  )
                                : Object(n.createElement)(n.Fragment, null, Object(n.createElement)(h, { isLoading: k, displayStyle: t.displayStyle }), B, P, $, W)
                        ),
                        k
                            ? Object(n.createElement)("span", { className: "wc-block-active-filters__clear-all-placeholder" })
                            : Object(n.createElement)(
                                  "button",
                                  {
                                      className: "wc-block-active-filters__clear-all",
                                      onClick: () => {
                                          Object(O.a)(), j || (C(void 0), L(void 0), S([]), N([]), T([]));
                                      },
                                  },
                                  Object(n.createElement)(s.a, { label: Object(c.__)("Clear All", "woocommerce"), screenReaderLabel: Object(c.__)("Clear All Filters", "woocommerce") })
                              )
                    )
                );
            };
        },
        20: function (e, t, r) {
            "use strict";
            var n = r(0),
                c = r(5),
                o = r.n(c);
            t.a = (e) => {
                let t,
                    { label: r, screenReaderLabel: c, wrapperElement: i, wrapperProps: a = {} } = e;
                const l = null != r,
                    s = null != c;
                return !l && s
                    ? ((t = i || "span"), (a = { ...a, className: o()(a.className, "screen-reader-text") }), Object(n.createElement)(t, a, c))
                    : ((t = i || n.Fragment),
                      l && s && r !== c
                          ? Object(n.createElement)(t, a, Object(n.createElement)("span", { "aria-hidden": "true" }, r), Object(n.createElement)("span", { className: "screen-reader-text" }, c))
                          : Object(n.createElement)(t, a, r));
            };
        },
        21: function (e, t, r) {
            "use strict";
            r.d(t, "a", function () {
                return c;
            }),
                r.d(t, "b", function () {
                    return o;
                });
            var n = r(38);
            const c = (e) => !Object(n.a)(e) && e instanceof Object && e.constructor === Object;
            function o(e, t) {
                return c(e) && t in e;
            }
        },
        225: function (e, t) {},
        226: function (e, t, r) {
            "use strict";
            var n = r(0),
                c = r(12);
            const o = Object(n.createElement)(
                c.SVG,
                { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24" },
                Object(n.createElement)(c.Path, { d: "M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z" })
            );
            t.a = o;
        },
        227: function (e, t) {},
        236: function (e, t, r) {
            "use strict";
            var n = r(13),
                c = r.n(n),
                o = r(0),
                i = r(5),
                a = r.n(i),
                l = r(1),
                s = r(82),
                u = r(226);
            r(225);
            var b = (e) => {
                let { text: t, screenReaderText: r = "", element: n = "li", className: i = "", radius: l = "small", children: s = null, ...u } = e;
                const b = n,
                    f = a()(i, "wc-block-components-chip", "wc-block-components-chip--radius-" + l),
                    d = Boolean(r && r !== t);
                return Object(o.createElement)(
                    b,
                    c()({ className: f }, u),
                    Object(o.createElement)("span", { "aria-hidden": d, className: "wc-block-components-chip__text" }, t),
                    d && Object(o.createElement)("span", { className: "screen-reader-text" }, r),
                    s
                );
            };
            t.a = (e) => {
                let { ariaLabel: t = "", className: r = "", disabled: n = !1, onRemove: i = () => {}, removeOnAnyClick: f = !1, text: d, screenReaderText: p = "", ...m } = e;
                const O = f ? "span" : "button";
                if (!t) {
                    const e = p && "string" == typeof p ? p : d;
                    t =
                        "string" != typeof e
                            ? /* translators: Remove chip. */
                              Object(l.__)("Remove", "woocommerce")
                            : Object(l.sprintf)(
                                  /* translators: %s text of the chip to remove. */
                                  Object(l.__)('Remove "%s"', "woocommerce"),
                                  e
                              );
                }
                const g = {
                        "aria-label": t,
                        disabled: n,
                        onClick: i,
                        onKeyDown: (e) => {
                            ("Backspace" !== e.key && "Delete" !== e.key) || i();
                        },
                    },
                    j = f ? g : {},
                    y = f ? { "aria-hidden": !0 } : g;
                return Object(o.createElement)(
                    b,
                    c()({}, m, j, { className: a()(r, "is-removable"), element: f ? "button" : m.element, screenReaderText: p, text: d }),
                    Object(o.createElement)(O, c()({ className: "wc-block-components-chip__remove" }, y), Object(o.createElement)(s.a, { className: "wc-block-components-chip__remove-icon", icon: u.a, size: 16 }))
                );
            };
        },
        24: function (e, t, r) {
            "use strict";
            r.d(t, "a", function () {
                return o;
            });
            var n = r(0);
            const c = Object(n.createContext)("page"),
                o = () => Object(n.useContext)(c);
            c.Provider;
        },
        27: function (e, t, r) {
            "use strict";
            r.d(t, "a", function () {
                return i;
            });
            var n = r(0),
                c = r(14),
                o = r.n(c);
            function i(e) {
                const t = Object(n.useRef)(e);
                return o()(e, t.current) || (t.current = e), t.current;
            }
        },
        28: function (e, t, r) {
            "use strict";
            r.d(t, "a", function () {
                return n;
            });
            const n = (e) => "string" == typeof e;
        },
        283: function (e, t, r) {
            "use strict";
            r.d(t, "a", function () {
                return n;
            });
            var n = function () {
                return (n =
                    Object.assign ||
                    function (e) {
                        for (var t, r = 1, n = arguments.length; r < n; r++) for (var c in (t = arguments[r])) Object.prototype.hasOwnProperty.call(t, c) && (e[c] = t[c]);
                        return e;
                    }).apply(this, arguments);
            };
            Object.create, Object.create;
        },
        284: function (e, t, r) {
            "use strict";
            function n(e) {
                return e.toLowerCase();
            }
            r.d(t, "a", function () {
                return i;
            });
            var c = [/([a-z0-9])([A-Z])/g, /([A-Z])([A-Z][a-z])/g],
                o = /[^A-Z0-9]+/gi;
            function i(e, t) {
                void 0 === t && (t = {});
                for (
                    var r = t.splitRegexp,
                        i = void 0 === r ? c : r,
                        l = t.stripRegexp,
                        s = void 0 === l ? o : l,
                        u = t.transform,
                        b = void 0 === u ? n : u,
                        f = t.delimiter,
                        d = void 0 === f ? " " : f,
                        p = a(a(e, i, "$1\0$2"), s, "\0"),
                        m = 0,
                        O = p.length;
                    "\0" === p.charAt(m);

                )
                    m++;
                for (; "\0" === p.charAt(O - 1); ) O--;
                return p.slice(m, O).split("\0").map(b).join(d);
            }
            function a(e, t, r) {
                return t instanceof RegExp
                    ? e.replace(t, r)
                    : t.reduce(function (e, t) {
                          return e.replace(t, r);
                      }, e);
            }
        },
        288: function (e, t, r) {
            "use strict";
            r.d(t, "a", function () {
                return o;
            });
            var n = r(283),
                c = r(284);
            function o(e, t) {
                return (
                    void 0 === t && (t = {}),
                    (function (e, t) {
                        return void 0 === t && (t = {}), Object(c.a)(e, Object(n.a)({ delimiter: "." }, t));
                    })(e, Object(n.a)({ delimiter: "-" }, t))
                );
            }
        },
        290: function (e, t, r) {
            "use strict";
            r.d(t, "a", function () {
                return b;
            });
            var n = r(5),
                c = r.n(n),
                o = r(21),
                i = r(28),
                a = r(288),
                l = r(132);
            function s() {
                let e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {};
                const t = {};
                return (
                    Object(l.getCSSRules)(e, { selector: "" }).forEach((e) => {
                        t[e.key] = e.value;
                    }),
                    t
                );
            }
            function u(e, t) {
                return e && t ? `has-${Object(a.a)(t)}-${e}` : "";
            }
            const b = (e) => {
                const t = ((e) => {
                        const t = Object(o.a)(e) ? e : { style: {} };
                        let r = t.style;
                        return Object(i.a)(r) && (r = JSON.parse(r) || {}), Object(o.a)(r) || (r = {}), { ...t, style: r };
                    })(e),
                    r = (function (e) {
                        var t, r, n, i, a, l, b;
                        const { backgroundColor: f, textColor: d, gradient: p, style: m } = e,
                            O = u("background-color", f),
                            g = u("color", d),
                            j = (function (e) {
                                if (e) return `has-${e}-gradient-background`;
                            })(p),
                            y = j || (null == m || null === (t = m.color) || void 0 === t ? void 0 : t.gradient);
                        return {
                            className: c()(g, j, {
                                [O]: !y && !!O,
                                "has-text-color": d || (null == m || null === (r = m.color) || void 0 === r ? void 0 : r.text),
                                "has-background": f || (null == m || null === (n = m.color) || void 0 === n ? void 0 : n.background) || p || (null == m || null === (i = m.color) || void 0 === i ? void 0 : i.gradient),
                                "has-link-color": Object(o.a)(null == m || null === (a = m.elements) || void 0 === a ? void 0 : a.link)
                                    ? null == m || null === (l = m.elements) || void 0 === l || null === (b = l.link) || void 0 === b
                                        ? void 0
                                        : b.color
                                    : void 0,
                            }),
                            style: s({ color: (null == m ? void 0 : m.color) || {} }),
                        };
                    })(t),
                    n = (function (e) {
                        var t;
                        const r = (null === (t = e.style) || void 0 === t ? void 0 : t.border) || {};
                        return {
                            className: (function (e) {
                                var t;
                                const { borderColor: r, style: n } = e,
                                    o = r ? u("border-color", r) : "";
                                return c()({ "has-border-color": r || (null == n || null === (t = n.border) || void 0 === t ? void 0 : t.color), borderColorClass: o });
                            })(e),
                            style: s({ border: r }),
                        };
                    })(t),
                    a = (function (e) {
                        var t;
                        return { className: void 0, style: s({ spacing: (null === (t = e.style) || void 0 === t ? void 0 : t.spacing) || {} }) };
                    })(t),
                    l = ((e) => {
                        const t = Object(o.a)(e.style.typography) ? e.style.typography : {},
                            r = Object(i.a)(t.fontFamily) ? t.fontFamily : "";
                        return {
                            className: e.fontFamily ? `has-${e.fontFamily}-font-family` : r,
                            style: {
                                fontSize: e.fontSize ? `var(--wp--preset--font-size--${e.fontSize})` : t.fontSize,
                                fontStyle: t.fontStyle,
                                fontWeight: t.fontWeight,
                                letterSpacing: t.letterSpacing,
                                lineHeight: t.lineHeight,
                                textDecoration: t.textDecoration,
                                textTransform: t.textTransform,
                            },
                        };
                    })(t);
                return { className: c()(l.className, r.className, n.className, a.className), style: { ...l.style, ...r.style, ...n.style, ...a.style } };
            };
        },
        33: function (e, t, r) {
            "use strict";
            r.d(t, "a", function () {
                return b;
            }),
                r.d(t, "b", function () {
                    return f;
                }),
                r.d(t, "c", function () {
                    return d;
                });
            var n = r(3),
                c = r(4),
                o = r(0),
                i = r(14),
                a = r.n(i),
                l = r(27),
                s = r(57),
                u = r(24);
            const b = (e) => {
                    const t = Object(u.a)();
                    e = e || t;
                    const r = Object(c.useSelect)((t) => t(n.QUERY_STATE_STORE_KEY).getValueForQueryContext(e, void 0), [e]),
                        { setValueForQueryContext: i } = Object(c.useDispatch)(n.QUERY_STATE_STORE_KEY);
                    return [
                        r,
                        Object(o.useCallback)(
                            (t) => {
                                i(e, t);
                            },
                            [e, i]
                        ),
                    ];
                },
                f = (e, t, r) => {
                    const i = Object(u.a)();
                    r = r || i;
                    const a = Object(c.useSelect)((c) => c(n.QUERY_STATE_STORE_KEY).getValueForQueryKey(r, e, t), [r, e]),
                        { setQueryValue: l } = Object(c.useDispatch)(n.QUERY_STATE_STORE_KEY);
                    return [
                        a,
                        Object(o.useCallback)(
                            (t) => {
                                l(r, e, t);
                            },
                            [r, e, l]
                        ),
                    ];
                },
                d = (e, t) => {
                    const r = Object(u.a)();
                    t = t || r;
                    const [n, c] = b(t),
                        i = Object(l.a)(n),
                        f = Object(l.a)(e),
                        d = Object(s.a)(f),
                        p = Object(o.useRef)(!1);
                    return (
                        Object(o.useEffect)(() => {
                            a()(d, f) || (c(Object.assign({}, i, f)), (p.current = !0));
                        }, [i, f, d, c]),
                        p.current ? [n, c] : [e, c]
                    );
                };
        },
        38: function (e, t, r) {
            "use strict";
            r.d(t, "a", function () {
                return n;
            });
            const n = (e) => null === e;
        },
        44: function (e, t, r) {
            "use strict";
            r.d(t, "b", function () {
                return p;
            }),
                r.d(t, "f", function () {
                    return m;
                }),
                r.d(t, "e", function () {
                    return O;
                }),
                r.d(t, "a", function () {
                    return y;
                }),
                r.d(t, "c", function () {
                    return v;
                }),
                r.d(t, "g", function () {
                    return h;
                }),
                r.d(t, "d", function () {
                    return _;
                });
            var n = r(0),
                c = r(1),
                o = r(39),
                i = r(236),
                a = r(20),
                l = r(15),
                s = r(69),
                u = r(82),
                b = r(226),
                f = r(28),
                d = r(140);
            const p = (e, t) =>
                    Number.isFinite(e) && Number.isFinite(t)
                        ? Object(c.sprintf)(
                              /* translators: %1$s min price, %2$s max price */
                              Object(c.__)("Between %1$s and %2$s", "woocommerce"),
                              Object(o.formatPrice)(woocs_convert_price_filter(e)),
                              Object(o.formatPrice)(woocs_convert_price_filter(t))
                          )
                        : Number.isFinite(e)
                        ? Object(c.sprintf)(
                              /* translators: %s min price */
                              Object(c.__)("From %s", "woocommerce"),
                              Object(o.formatPrice)(woocs_convert_price_filter(e))
                          )
                        : Object(c.sprintf)(
                              /* translators: %s max price */
                              Object(c.__)("Up to %s", "woocommerce"),
                              Object(o.formatPrice)(woocs_convert_price_filter(t))
                          ),
                m = (e) => {
                    let { type: t, name: r, prefix: o = "", removeCallback: l = () => null, showLabel: s = !0, displayStyle: f } = e;
                    const d = o ? Object(n.createElement)(n.Fragment, null, o, " ", r) : r,
                        p = Object(c.sprintf)(
                            /* translators: %s attribute value used in the filter. For example: yellow, green, small, large. */
                            Object(c.__)("Remove %s filter", "woocommerce"),
                            r
                        );
                    return Object(n.createElement)(
                        "li",
                        { className: "wc-block-active-filters__list-item", key: t + ":" + r },
                        s && Object(n.createElement)("span", { className: "wc-block-active-filters__list-item-type" }, t + ": "),
                        "chips" === f
                            ? Object(n.createElement)(i.a, { element: "span", text: d, onRemove: l, radius: "large", ariaLabel: p })
                            : Object(n.createElement)(
                                  "span",
                                  { className: "wc-block-active-filters__list-item-name" },
                                  Object(n.createElement)(
                                      "button",
                                      { className: "wc-block-active-filters__list-item-remove", onClick: l },
                                      Object(n.createElement)(u.a, { className: "wc-block-components-chip__remove-icon", icon: b.a, size: 16 }),
                                      Object(n.createElement)(a.a, { screenReaderLabel: p })
                                  ),
                                  d
                              )
                    );
                },
                O = function () {
                    if (!window) return;
                    const e = window.location.href,
                        t = Object(l.getQueryArgs)(e),
                        r = Object(l.removeQueryArgs)(e, ...Object.keys(t));
                    for (var n = arguments.length, c = new Array(n), o = 0; o < n; o++) c[o] = arguments[o];
                    c.forEach((e) => {
                        if ("string" == typeof e) return delete t[e];
                        if ("object" == typeof e) {
                            const r = Object.keys(e)[0],
                                n = t[r].toString().split(",");
                            t[r] = n.filter((t) => t !== e[r]).join(",");
                        }
                    });
                    const i = Object.fromEntries(
                            Object.entries(t).filter((e) => {
                                let [, t] = e;
                                return t;
                            })
                        ),
                        a = Object(l.addQueryArgs)(r, i);
                    Object(s.c)(a);
                },
                g = ["min_price", "max_price", "rating_filter", "filter_", "query_type_"],
                j = (e) => {
                    let t = !1;
                    for (let r = 0; g.length > r; r++) {
                        const n = g[r];
                        if (n === e.substring(0, n.length)) {
                            t = !0;
                            break;
                        }
                    }
                    return t;
                },
                y = () => {
                    if (!window) return;
                    const e = window.location.href,
                        t = Object(l.getQueryArgs)(e),
                        r = Object(l.removeQueryArgs)(e, ...Object.keys(t)),
                        n = Object.fromEntries(
                            Object.keys(t)
                                .filter((e) => !j(e))
                                .map((e) => [e, t[e]])
                        ),
                        c = Object(l.addQueryArgs)(r, n);
                    Object(s.c)(c);
                },
                v = () => {
                    if (!window) return !1;
                    const e = window.location.href,
                        t = Object(l.getQueryArgs)(e),
                        r = Object.keys(t);
                    let n = !1;
                    for (let e = 0; r.length > e; e++) {
                        const t = r[e];
                        if (j(t)) {
                            n = !0;
                            break;
                        }
                    }
                    return n;
                },
                h = (e) => {
                    if (!window) return !1;
                    const t = e.map((e) => "filter_" + e.attribute_name),
                        r = window.location.href,
                        n = Object(l.getQueryArgs)(r),
                        c = Object.keys(n);
                    let o = !1;
                    for (let e = 0; c.length > e; e++) {
                        const r = c[e];
                        if (t.includes(r)) {
                            o = !0;
                            break;
                        }
                    }
                    return o;
                },
                _ = (e) => ({
                    heading: Object(f.a)(null == e ? void 0 : e.heading) ? e.heading : "",
                    headingLevel: (Object(f.a)(null == e ? void 0 : e.headingLevel) && parseInt(e.headingLevel, 10)) || d.attributes.headingLevel.default,
                    displayStyle: (Object(f.a)(null == e ? void 0 : e.displayStyle) && e.displayStyle) || d.attributes.displayStyle.default,
                });
        },
        45: function (e, t, r) {
            "use strict";
            r.d(t, "a", function () {
                return s;
            });
            var n = function (e) {
                    return function (t, r, n) {
                        return e(t, r, n) * n;
                    };
                },
                c = function (e, t) {
                    if (e) throw Error("Invalid sort config: " + t);
                },
                o = function (e) {
                    var t = e || {},
                        r = t.asc,
                        o = t.desc,
                        i = r ? 1 : -1,
                        a = r || o;
                    return c(!a, "Expected `asc` or `desc` property"), c(r && o, "Ambiguous object with `asc` and `desc` config properties"), { order: i, sortBy: a, comparer: e.comparer && n(e.comparer) };
                };
            var i = function (e, t, r, n) {
                return Array.isArray(t)
                    ? (Array.isArray(r) && r.length < 2 && (r = r[0]),
                      t.sort(
                          (function e(t, r, n) {
                              if (void 0 === t || !0 === t)
                                  return function (e, t) {
                                      return r(e, t, n);
                                  };
                              if ("string" == typeof t)
                                  return (
                                      c(t.includes("."), "String syntax not allowed for nested properties."),
                                      function (e, c) {
                                          return r(e[t], c[t], n);
                                      }
                                  );
                              if ("function" == typeof t)
                                  return function (e, c) {
                                      return r(t(e), t(c), n);
                                  };
                              if (Array.isArray(t)) {
                                  var i = (function (e) {
                                      return function t(r, n, c, i, a, l, s) {
                                          var u, b;
                                          if ("string" == typeof r) (u = l[r]), (b = s[r]);
                                          else {
                                              if ("function" != typeof r) {
                                                  var f = o(r);
                                                  return t(f.sortBy, n, c, f.order, f.comparer || e, l, s);
                                              }
                                              (u = r(l)), (b = r(s));
                                          }
                                          var d = a(u, b, i);
                                          return (0 === d || (null == u && null == b)) && n.length > c ? t(n[c], n, c + 1, i, a, l, s) : d;
                                      };
                                  })(r);
                                  return function (e, c) {
                                      return i(t[0], t, 1, n, r, e, c);
                                  };
                              }
                              var a = o(t);
                              return e(a.sortBy, a.comparer || r, a.order);
                          })(r, n, e)
                      ))
                    : t;
            };
            function a(e) {
                var t = n(e.comparer);
                return function (r) {
                    var n = Array.isArray(r) && !e.inPlaceSorting ? r.slice() : r;
                    return {
                        asc: function (e) {
                            return i(1, n, e, t);
                        },
                        desc: function (e) {
                            return i(-1, n, e, t);
                        },
                        by: function (e) {
                            return i(1, n, e, t);
                        },
                    };
                };
            }
            var l = function (e, t, r) {
                    return null == e ? r : null == t ? -r : typeof e != typeof t ? (typeof e < typeof t ? -1 : 1) : e < t ? -1 : e > t ? 1 : 0;
                },
                s = a({ comparer: l });
            a({ comparer: l, inPlaceSorting: !0 });
        },
        481: function (e, t, r) {
            "use strict";
            r.r(t);
            var n = r(0),
                c = r(5),
                o = r.n(c),
                i = r(290),
                a = r(28),
                l = r(160),
                s = r(44);
            t.default = (e) => {
                const t = Object(i.a)(e),
                    r = Object(s.d)(e);
                return Object(n.createElement)("div", { className: o()(Object(a.a)(e.className) ? e.className : "", t.className), style: t.style }, Object(n.createElement)(l.a, { isEditor: !1, attributes: r }));
            };
        },
        5: function (e, t, r) {
            var n;
            !(function () {
                "use strict";
                var r = {}.hasOwnProperty;
                function c() {
                    for (var e = [], t = 0; t < arguments.length; t++) {
                        var n = arguments[t];
                        if (n) {
                            var o = typeof n;
                            if ("string" === o || "number" === o) e.push(n);
                            else if (Array.isArray(n)) {
                                if (n.length) {
                                    var i = c.apply(null, n);
                                    i && e.push(i);
                                }
                            } else if ("object" === o)
                                if (n.toString === Object.prototype.toString) for (var a in n) r.call(n, a) && n[a] && e.push(a);
                                else e.push(n.toString());
                        }
                    }
                    return e.join(" ");
                }
                e.exports
                    ? ((c.default = c), (e.exports = c))
                    : void 0 ===
                          (n = function () {
                              return c;
                          }.apply(t, [])) || (e.exports = n);
            })();
        },
        56: function (e, t, r) {
            "use strict";
            r.d(t, "a", function () {
                return a;
            });
            var n = r(3),
                c = r(4),
                o = r(0),
                i = r(27);
            const a = (e) => {
                const { namespace: t, resourceName: r, resourceValues: a = [], query: l = {}, shouldSelect: s = !0 } = e;
                if (!t || !r) throw new Error("The options object must have valid values for the namespace and the resource properties.");
                const u = Object(o.useRef)({ results: [], isLoading: !0 }),
                    b = Object(i.a)(l),
                    f = Object(i.a)(a),
                    d = (() => {
                        const [, e] = Object(o.useState)();
                        return Object(o.useCallback)((t) => {
                            e(() => {
                                throw t;
                            });
                        }, []);
                    })(),
                    p = Object(c.useSelect)(
                        (e) => {
                            if (!s) return null;
                            const c = e(n.COLLECTIONS_STORE_KEY),
                                o = [t, r, b, f],
                                i = c.getCollectionError(...o);
                            if (i) {
                                if (!(i instanceof Error)) throw new Error("TypeError: `error` object is not an instance of Error constructor");
                                d(i);
                            }
                            return { results: c.getCollection(...o), isLoading: !c.hasFinishedResolution("getCollection", o) };
                        },
                        [t, r, f, b, s]
                    );
                return null !== p && (u.current = p), u.current;
            };
        },
        57: function (e, t, r) {
            "use strict";
            r.d(t, "a", function () {
                return c;
            });
            var n = r(0);
            function c(e, t) {
                const r = Object(n.useRef)();
                return (
                    Object(n.useEffect)(() => {
                        r.current === e || (t && !t(e, r.current)) || (r.current = e);
                    }, [e, t]),
                    r.current
                );
            }
        },
        62: function (e, t, r) {
            "use strict";
            var n = r(0);
            r(90),
                (t.a = (e) => {
                    let { children: t } = e;
                    return Object(n.createElement)("div", { className: "wc-block-filter-title-placeholder" }, t);
                });
        },
        65: function (e, t, r) {
            "use strict";
            r.d(t, "a", function () {
                return n;
            });
            const n = (e) => "boolean" == typeof e;
        },
        69: function (e, t, r) {
            "use strict";
            r.d(t, "b", function () {
                return a;
            }),
                r.d(t, "a", function () {
                    return l;
                }),
                r.d(t, "d", function () {
                    return s;
                }),
                r.d(t, "c", function () {
                    return u;
                }),
                r.d(t, "e", function () {
                    return b;
                });
            var n = r(15),
                c = r(2),
                o = r(65);
            const i = Object(c.getSettingWithCoercion)("is_rendering_php_template", !1, o.a),
                a = "query_type_",
                l = "filter_";
            function s(e) {
                return window ? Object(n.getQueryArg)(window.location.href, e) : null;
            }
            function u(e) {
                i ? (window.location.href = e) : window.history.replaceState({}, "", e);
            }
            const b = (e) => {
                const t = Object(n.getQueryArgs)(e);
                return Object(n.addQueryArgs)(e, t);
            };
        },
        82: function (e, t, r) {
            "use strict";
            var n = r(0);
            t.a = function (e) {
                let { icon: t, size: r = 24, ...c } = e;
                return Object(n.cloneElement)(t, { width: r, height: r, ...c });
            };
        },
        90: function (e, t) {},
    },
]);
