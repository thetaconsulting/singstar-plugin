wp.blocks.registerBlockType("ourdatabaseplugin/singstar", {
  title: "Singstar catalogue",
  edit: function () {
    return wp.element.createElement("div", { className: "our-placeholder-block" }, "Pets List Placeholder")
  },
  save: function () {
    return null
  }
})
