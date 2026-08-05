(() => {
  const toggle = document.querySelector(".menu-toggle");
  const navigation = document.querySelector(".primary-navigation");

  if (toggle && navigation) {
    const closeMenu = () => {
      navigation.classList.remove("is-open");
      toggle.setAttribute("aria-expanded", "false");
      document.body.classList.remove("menu-open");
    };

    toggle.addEventListener("click", () => {
      const willOpen = !navigation.classList.contains("is-open");
      navigation.classList.toggle("is-open", willOpen);
      toggle.setAttribute("aria-expanded", String(willOpen));
      document.body.classList.toggle("menu-open", willOpen);
    });

    navigation.addEventListener("click", (event) => {
      if (event.target.closest("a")) {
        closeMenu();
      }
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        closeMenu();
        toggle.focus();
      }
    });

    window.addEventListener("resize", () => {
      if (window.innerWidth > 860) {
        closeMenu();
      }
    });
  }

  const cartCountNodes = () => document.querySelectorAll(".header-cart__count");
  let cartSyncTimer;

  const renderCartCount = (count) => {
    cartCountNodes().forEach((node) => {
      node.textContent = String(count);
    });
  };

  const syncCartCount = async () => {
    if (cartCountNodes().length === 0) {
      return;
    }

    try {
      const response = await fetch("/wp-json/wc/store/v1/cart", {
        credentials: "same-origin",
        headers: { Accept: "application/json" },
      });
      if (!response.ok) {
        return;
      }
      const cart = await response.json();
      if (Number.isFinite(cart.items_count)) {
        renderCartCount(cart.items_count);
      }
    } catch {
      // Keep the server-rendered value when Store API synchronization is unavailable.
    }
  };

  const scheduleCartSync = () => {
    window.clearTimeout(cartSyncTimer);
    cartSyncTimer = window.setTimeout(syncCartCount, 250);
  };

  document.body.addEventListener("wc-blocks_added_to_cart", scheduleCartSync);
  document.body.addEventListener("wc-blocks_removed_from_cart", scheduleCartSync);
  window.addEventListener("pageshow", scheduleCartSync);

  const cartBlock = document.querySelector(".wp-block-woocommerce-cart");
  if (cartBlock) {
    new MutationObserver(scheduleCartSync).observe(cartBlock, {
      childList: true,
      subtree: true,
    });
  }
})();
