wp.blocks.registerBlockType("ourdatabaseplugin/singstar", {
  title: "Singstar catalogue",
  edit: function () {
    return wp.element.createElement("div", { className: "our-placeholder-block" }, "Singstar catalogue Placeholder")
  },
  save: function () {
    return null
  }
})
