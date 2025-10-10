// plopfile.js (CommonJS)
module.exports = function (plop) {
  // Helpers
  plop.setHelper("kebab", (txt) =>
    String(txt)
      .trim()
      .replace(/[^a-zA-Z0-9]+/g, "-")
      .replace(/^-+|-+$/g, "")
      .toLowerCase()
  );
  plop.setHelper("pascal", (txt) =>
    String(txt).replace(/(^\w|[-_\s]\w)/g, (m) =>
      m.replace(/[-_\s]/, "").toUpperCase()
    )
  );
  plop.setHelper("snake", (txt) =>
    String(txt)
      .trim()
      .replace(/[^a-zA-Z0-9]+/g, "_")
      .replace(/^_+|_+$/g, "")
      .toLowerCase()
  );

  plop.setGenerator("block", {
    description: "Generate minimal ACF block (+ optional JS + SCSS stubs)",
    prompts: [
      {
        type: "input",
        name: "name",
        message: "Block name (e.g. Hero)",
        validate: (v) => !!v || "Required",
      },
      {
        type: "confirm",
        name: "useJS",
        message: "Add index.js?",
        default: false,
      },
      {
        type: "confirm",
        name: "useSCSS",
        message: "Add SCSS stubs?",
        default: true,
      },
    ],
    actions: (data) => {
      const base = "blocks/{{kebab name}}";
      const actions = [
        {
          type: "add",
          path: `${base}/block.json`,
          templateFile: "plop-templates/block/block.json.hbs",
        },
        {
          type: "add",
          path: `${base}/render.php`,
          templateFile: "plop-templates/block/render.php.hbs",
        },
      ];

      if (data.useJS) {
        actions.push({
          type: "add",
          path: `${base}/index.js`,
          templateFile: "plop-templates/block/index.js.hbs",
        });
      }

      if (data.useSCSS) {
        actions.push({
          type: "add",
          path: `gutenberg-styles/{{kebab name}}.scss`,
          templateFile: "plop-templates/block/style.scss.hbs",
        });
        // actions.push({
        //   type: "add",
        //   path: `gutenberg-styles/{{kebab name}}.editor.scss`,
        //   templateFile: "plop-templates/block/editor.scss.hbs",
        // });
      }

      return actions;
    },
  });
};
