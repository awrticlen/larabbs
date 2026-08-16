(function (window) {
  'use strict'

  const getImageFiles = (files) => Array.prototype.filter.call(files || [], (file) => (
    /^image\//.test(file.type) || /\.(?:bmp|gif|jpe?g|png)$/i.test(file.name)
  ))

  const getFirstError = (errors) => Object.keys(errors || {})
    .map((field) => errors[field])
    .flat()
    .find((message) => typeof message === 'string' && message)

  const getUploadErrorMessage = (xhr) => {
    if (!xhr || !xhr.responseText) {
      return '图片上传失败，请稍后重试'
    }

    try {
      const response = JSON.parse(xhr.responseText)
      return getFirstError(response.errors) || response.message || '图片上传失败，请稍后重试'
    } catch (error) {
      return '图片上传失败，请稍后重试'
    }
  }

  const setDropRange = (editor, event) => {
    const body = editor.body[0]
    const document = body.ownerDocument
    const range = document.caretRangeFromPoint
      ? document.caretRangeFromPoint(event.clientX, event.clientY)
      : null

    if (range && body.contains(range.startContainer)) {
      editor.selection.range(range)
    }
  }

  const registerDropUpload = (editor) => {
    const body = editor.body[0]

    body.addEventListener('dragover', (event) => {
      if (!getImageFiles(event.dataTransfer.files).length) {
        return
      }

      event.preventDefault()
      event.dataTransfer.dropEffect = 'copy'
    })

    body.addEventListener('drop', (event) => {
      const files = getImageFiles(event.dataTransfer.files)

      if (!files.length) {
        return
      }

      event.preventDefault()
      event.stopPropagation()
      setDropRange(editor, event)
      files.forEach((file) => editor.uploader.upload(file, { inline: true }))
    }, true)
  }

  window.createTopicImageEditor = ({ textarea, uploadUrl, csrfToken }) => {
    const editor = new window.Simditor({
      textarea,
      pasteImage: true,
      upload: {
        url: uploadUrl,
        params: {
          _token: csrfToken,
          type: 'topic'
        },
        fileKey: 'image'
      }
    })

    registerDropUpload(editor)
    editor.uploader.on('uploaderror', (event, file, xhr) => {
      window.alert(getUploadErrorMessage(xhr))
    })

    return editor
  }
}(window))