jQuery(function ($) {
  const $body = $(document.body);
  const setQtyButtonsDisabled = (disabled) => {
    // zawsze deleguj po aktualnym DOM
    $(".cart-qty.plus, .cart-qty.minus").prop("disabled", !!disabled);
  };

  // Gdy checkout zaczyna się aktualizować (Twoje update_checkout)
  $body.on("update_checkout", function () {
    setQtyButtonsDisabled(true);
  });

  // Gdy Woo zakończy aktualizację i podmieni HTML
  $body.on("updated_checkout", function () {
    setQtyButtonsDisabled(false);
  });

  // Bezpiecznik: gdyby request padł
  $body.on("checkout_error", function () {
    setQtyButtonsDisabled(false);
  });
  // Twoja logika zmiany qty + AJAX
  let t;
  $("form.checkout").on(
    "change",
    ".input-text.qty.text, input.qty",
    function () {
      const $input = $(this);
      const cartItemKey = $input.attr("name"); // u Ciebie to jest cart_item_key
      const qty = parseInt($input.val(), 10);

      if (!cartItemKey || !Number.isFinite(qty) || qty < 0) return;

      clearTimeout(t);
      t = setTimeout(() => {
        setQtyButtonsDisabled(true);

        $.post(add_quantity.ajax_url, {
          action: "aw_update_checkout_qty",
          cart_item_key: cartItemKey,
          qty: qty,
        })

          .done(() => {
            // zawsze odpal update_checkout — odblokuje się na updated_checkout

            $body.trigger("update_checkout");
          });
      }, 200);
    },
  );
});
