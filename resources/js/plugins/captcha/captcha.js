import  './captcha.css'

const w = 310 // canvas宽度
const h = 155 // canvas高度
const l = 42 // 滑块边长
const r = 9 // 滑块半径
const PI = Math.PI
const L = l + r * 2 + 3 // 滑块实际边长

function getRandomNumberByRange (start, end) {
  return Math.round(Math.random() * (end - start) + start)
}

function createCanvas (width, height) {
  const canvas = document.createElement('canvas')
  canvas.width = width
  canvas.height = height
  return canvas
}

function createImg (onload) {
  const img = new Image()
  img.crossOrigin = 'Anonymous'
  img.onload = onload
  img.onerror = () => {
   img.setSrc(getRandomImgSrc()) // 图片加载失败的时候重新加载其他图片
  }

  img.setSrc = function (src) {
    // const isIE = window.navigator.userAgent.indexOf('Trident') > -1
    // if (isIE) { // IE浏览器无法通过img.crossOrigin跨域，使用ajax获取图片blob然后转为dataURL显示
    //   const xhr = new XMLHttpRequest()
    //   xhr.onloadend = function (e) {
    //     const file = new FileReader() // FileReader仅支持IE10+
    //     file.readAsDataURL(e.target.response)
    //     file.onloadend = function (e) {
    //       img.src = e.target.result
    //     }
    //   }
    //   xhr.open('GET', src)
    //   xhr.responseType = 'blob'
    //   xhr.send()
    // }
    // else img.src = src
    img.src = src
  }

  img.setSrc(getRandomImgSrc())
  return img
}

function createElement (tagName, className) {
  const element = document.createElement(tagName)
  className && (element.className = className)
//   className && (element.className = styles[className])
  return element
}

function setClass (element, className) {
    // console.log(element,styles[className])
  element.className = className
}

function addClass (element, className) {
  element.classList.add(className)
//   element.classList.add(styles[className])
}

function removeClass (element, className) {
  element.classList.remove(className)
//   element.classList.remove(styles[className])
}

function getRandomImgSrc () {
  return require('@/assets/captcha/captcha'+(Math.floor(Math.random() * (5 - 1 + 1)) + 1)+'.jpg').default
}

function drawPath (ctx, x, y, operation) {
  ctx.beginPath()
  ctx.moveTo(x, y)
  ctx.arc(x + l / 2, y - r + 2, r, 0.72 * PI, 2.26 * PI)
  ctx.lineTo(x + l, y)
  ctx.arc(x + l + r - 2, y + l / 2, r, 1.21 * PI, 2.78 * PI)
  ctx.lineTo(x + l, y + l)
  ctx.lineTo(x, y + l)
  ctx.arc(x + r - 2, y + l / 2, r + 0.4, 2.76 * PI, 1.24 * PI, true)
  ctx.lineTo(x, y)
  ctx.lineWidth = 2
  ctx.fillStyle = 'rgba(255, 255, 255, 0.7)'
  ctx.strokeStyle = 'rgba(255, 255, 255, 0.7)'
  ctx.stroke()
  ctx.globalCompositeOperation = 'destination-over'
  operation === 'fill'? ctx.fill() : ctx.clip()
}

function sum (x, y) {
  return x + y
}

function square (x) {
  return x * x
}

class Captcha {
  constructor ({ el, width = w, height = h, onSuccess, onFail, onRefresh }) {
    Object.assign(el.style, {
      position: 'absolute',
      boxShadow:'0px 12px 32px 4px rgba(0, 0, 0, .3), 0px 8px 20px rgba(0, 0, 0, .08)',
      width: width + 'px',
      background:'#fff',
      margin: '0 auto',
      padding:'20px',
      borderRadius:'6px',
      zIndex:'200',
      opacity: 0,
      transform:'translateY(-180px)'
    })
    this.width = width
    this.height = height
    this.el = el
    this.onSuccess = onSuccess
    this.onFail = onFail
    this.onRefresh = onRefresh
  }

  init () {
    this.initDOM()
    this.initImg()
    this.bindEvents()
    
    // this.el.style.transition = ' 0.3s ease',
    setTimeout(()=>{
        this.el.style.transition = ' 0.5s ease'
        this.el.style.opacity = 1
        this.el.style.transform = 'translateY(0)'
    },200)
    return this
  }

  initDOM () {
    const { width, height } = this
    const canvas = createCanvas(width, height) // 画布
    const block = createCanvas(width, height) // 滑块
    setClass(block, 'block')
    setClass(canvas, 'canvas2')
    const sliderContainer = createElement('div', 'sliderContainer')
    sliderContainer.style.width = width + 'px'
    sliderContainer.style.pointerEvents = 'none'
    const refreshIcon = createElement('div', 'refreshIcon')
    const sliderMask = createElement('div', 'sliderMask')
    const slider = createElement('div', 'slider')
    const sliderIcon = createElement('span', 'sliderIcon')
    const text = createElement('span', 'sliderText')
    const closeBtn = createElement('div', 'closebtn')
    text.innerHTML = '向右滑动填充拼图'

    // 增加loading
    const loadingContainer = createElement('div', 'loadingContainer')
    loadingContainer.style.width = width + 'px'
    loadingContainer.style.height = height + 'px'
    const loadingIcon = createElement('div', 'loadingIcon')
    const loadingText = createElement('span')
    loadingText.innerHTML = '加载中...'
    loadingContainer.appendChild(loadingIcon)
    loadingContainer.appendChild(loadingText)

    const el = this.el
    el.appendChild(closeBtn)
    el.appendChild(loadingContainer)
    el.appendChild(canvas)
    el.appendChild(refreshIcon)
    el.appendChild(block)
    slider.appendChild(sliderIcon)
    sliderMask.appendChild(slider)
    sliderContainer.appendChild(sliderMask)
    sliderContainer.appendChild(text)
    el.appendChild(sliderContainer)

    Object.assign(this, {
      closeBtn,
      canvas,
      block,
      sliderContainer,
      loadingContainer,
      refreshIcon,
      slider,
      sliderMask,
      sliderIcon,
      text,
      canvasCtx: canvas.getContext('2d'),
      blockCtx: block.getContext('2d')
    })
  }

  setLoading (isLoading) {
    this.loadingContainer.style.display = isLoading ? '' : 'none'
    this.sliderContainer.style.pointerEvents = isLoading ? 'none' : ''
  }

  initImg () {
    const img = createImg(() => {
      this.setLoading(false)
      this.draw(img)
    })
    this.img = img
  }

  draw (img) {
    const { width, height } = this
    // 随机位置创建拼图形状
    this.x = getRandomNumberByRange(L + 10, width - (L + 10))
    this.y = getRandomNumberByRange(10 + r * 2, height - (L + 10))
    drawPath(this.canvasCtx, this.x, this.y, 'fill')
    drawPath(this.blockCtx, this.x, this.y, 'clip')

    // 画入图片
    this.canvasCtx.drawImage(img, 0, 0, width, height)
    this.blockCtx.drawImage(img, 20, 20, width, height)

    // 提取滑块并放到最左边
    const y = this.y - r * 2 - 1
    const ImageData = this.blockCtx.getImageData(this.x - 3, y, L, L)
    this.block.width = L
    this.blockCtx.putImageData(ImageData, 0, y)
  }

  bindEvents () {
    this.el.onselectstart = () => false
    this.refreshIcon.onclick = () => {
      this.reset()
      typeof this.onRefresh === 'function' && this.onRefresh()
    }
    this.closeBtn.onclick = () => {
        this.el.style.transition = ' 0.3s ease'
        this.el.style.opacity = 0
        this.el.style.transform = 'translateY(-180px)'
        setTimeout(()=>{
            var childs = this.el.childNodes; 
            for(var i = childs.length - 1; i >= 0; i--) {
                this.el.removeChild(childs[i]);
            }
            this.el.removeAttribute("style")
        },400)
    }

    let originX, originY, trail = [], isMouseDown = false

    const handleDragStart = function (e) {
      originX = e.clientX || e.touches[0].clientX
      originY = e.clientY || e.touches[0].clientY
      isMouseDown = true
    }
    const width = this.width
    const handleDragMove = (e) => {
      if (!isMouseDown) return false
      e.preventDefault()
      const eventX = e.clientX || e.touches[0].clientX
      const eventY = e.clientY || e.touches[0].clientY
      const moveX = eventX - originX + 20
      const moveY = eventY - originY
      if (moveX < 0 || moveX + 38 >= width) return false
      this.slider.style.left = moveX + 'px'
      const blockLeft = (width - 40 - 20) / (width - 40) * moveX
      this.block.style.left = blockLeft + 'px'

      addClass(this.sliderContainer, 'sliderContainer_active')
      this.sliderMask.style.width = moveX + 6 + 'px'
      trail.push(moveY)
    }

    const handleDragEnd = (e) => {
      if (!isMouseDown) return false
      isMouseDown = false
      const eventX = e.clientX || e.changedTouches[0].clientX
      if (eventX === originX) return false
      removeClass(this.sliderContainer, 'sliderContainer_active')
      this.trail = trail
      const { spliced, verified } = this.verify()
      if (spliced) {
        if (verified) {
            addClass(this.sliderContainer, 'sliderContainer_success')
            typeof this.onSuccess === 'function' && this.onSuccess()
            this.el.style.transition = ' 0.3s ease'
            this.el.style.opacity = 0
            this.el.style.transform = 'translateY(-180px)'
            setTimeout(()=>{
                var childs = this.el.childNodes; 
                for(var i = childs.length - 1; i >= 0; i--) {
                    this.el.removeChild(childs[i]);
                }
                this.el.removeAttribute("style")
            },400)
        } else {
          addClass(this.sliderContainer, 'sliderContainer_fail')
          this.text.innerHTML = '请再试一次'
          this.reset()
        }
      } else {
        addClass(this.sliderContainer, 'sliderContainer_fail')
        typeof this.onFail === 'function' && this.onFail()
        setTimeout(this.reset.bind(this), 1000)
      }
    }
    this.slider.addEventListener('mousedown', handleDragStart)
    this.slider.addEventListener('touchstart', handleDragStart)
    this.block.addEventListener('mousedown', handleDragStart)
    this.block.addEventListener('touchstart', handleDragStart)
    document.addEventListener('mousemove', handleDragMove)
    document.addEventListener('touchmove', handleDragMove)
    document.addEventListener('mouseup', handleDragEnd)
    document.addEventListener('touchend', handleDragEnd)
  }

  verify () {
    const arr = this.trail // 拖动时y轴的移动距离
    const average = arr.reduce(sum) / arr.length
    const deviations = arr.map(x => x - average)
    const stddev = Math.sqrt(deviations.map(square).reduce(sum) / arr.length)
    const left = parseInt(this.block.style.left)
    return {
      spliced: Math.abs(left - (this.x + 20)) < 10,
      verified: stddev !== 0, // 简单验证拖动轨迹，为零时表示Y轴上下没有波动，可能非人为操作
    }
  }

  reset () {
    const { width, height } = this
    // 重置样式
    setClass(this.sliderContainer, 'sliderContainer')
    this.slider.style.left = 0 + 'px'
    this.block.width = width
    this.block.style.left = 20 + 'px'
    this.sliderMask.style.width = 0 + 'px'

    // 清空画布
    this.canvasCtx.clearRect(0, 0, width, height)
    this.blockCtx.clearRect(20, 0, width, height)

    // 重新加载图片
    this.setLoading(true)
    this.img.setSrc(getRandomImgSrc())
  }

  openCaptcha(){
    return this.x
  }
  closeCaptcha(){
    this.el.style.transition = ' 0.3s ease'
    this.el.style.opacity = 0
    this.el.style.transform = 'translateY(-180px)'
    setTimeout(()=>{
        var childs = this.el.childNodes; 
        for(var i = childs.length - 1; i >= 0; i--) {
            this.el.removeChild(childs[i]);
        }
        this.el.removeAttribute("style")
    },400)
    
  }

  
}

export default function createDom(opts){
    return new Captcha(opts).init()
}

