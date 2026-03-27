.hb-menu nav > ul > li.catalog-nav-link > a {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding-right: 0;
}

.hb-menu nav > ul > li.catalog-nav-link > a::after {
    position: static;
    margin: 0;
    transform: none;
}

.hb-menu .hb-megamenu--catalog {
    min-width: 960px;
    max-width: min(1120px, 92vw);
    max-height: 70vh;
    overflow-y: auto;
    overflow-x: hidden;
}

.hb-menu .hb-megamenu--catalog > li > a {
    font-weight: 700;
}

.hb-menu .hb-megamenu--catalog .catalog-nav__category > a {
    display: block;
    color: #242424;
    font-size: 13px;
    font-weight: 700;
    margin-top: 10px;
    text-transform: uppercase;
}

.hb-menu .hb-megamenu--catalog .catalog-nav__product > a,
.hb-menu .hb-megamenu--catalog .catalog-nav__all > a {
    display: block;
    color: #666;
    font-size: 13px;
    line-height: 1.8;
    padding-left: 14px;
}

.hb-menu .hb-megamenu--catalog .catalog-nav__all > a {
    color: #242424;
    font-weight: 600;
}

.hb-menu .hb-megamenu--catalog .catalog-nav__type-all > a {
    display: block;
    color: #111;
    font-size: 13px;
    font-weight: 700;
    line-height: 1.8;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #ececec;
}

.hb-menu .hb-megamenu--catalog .catalog-nav__product > a:hover,
.hb-menu .hb-megamenu--catalog .catalog-nav__all > a:hover,
.hb-menu .hb-megamenu--catalog .catalog-nav__category > a:hover,
.hb-menu .hb-megamenu--catalog .catalog-nav__type-all > a:hover {
    color: #fed700;
}

.header-middle-right .hm-menu {
    display: flex;
    align-items: center;
}

.header-middle-right .hm-menu .hm-wishlist {
    margin-left: 18px;
    margin-right: 4px;
}

@media (max-width: 1199px) {
    .hb-menu .hb-megamenu--catalog {
        min-width: 720px;
    }
}
