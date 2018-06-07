UE.registerUI('dialog', function (editor, uiName) {
  var btn = new UE.ui.Button({
    name: 'xiumi',
    title: '图文库',
    icon: 'newspaper-o',
    onclick: function () {
      var dialog = new UE.ui.Dialog({
        iframeUrl: '/static/ueditor/third-party/xiumi/iframe.html',
        editor: editor,
        name: 'xiumi-connect',
        title: '图文库',
        cssRules: 'width: ' + (window.innerWidth - 60) + 'px;' + 'height: ' + (window.innerHeight - 60) + 'px;'
      })
      dialog.render()
      dialog.open()
    }
  })

  return btn
})
