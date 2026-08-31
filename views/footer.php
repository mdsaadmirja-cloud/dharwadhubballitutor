<style type="text/css">
/* =========================================================
   DHT FOOTER — ENHANCED 4-COLUMN LAYOUT
   Background colors intentionally preserved from existing footer.
   ========================================================= */
.new-footer {
      background: #14163a;
      padding: 50px 0 24px;
}

.new-footer .footer-inner {
      width: 100%;
}

.new-footer .footer-grid {
      display: grid;
      grid-template-columns: 1.55fr 0.85fr 0.95fr 1.25fr;
      gap: 48px;
      align-items: start;
}

.new-footer .footer-col h5 {
      color: #ffffff;
      font-weight: 700;
      font-size: 1rem;
      margin: 0 0 18px;
      position: relative;
      display: inline-block;
}

.new-footer .footer-col h5::after {
      content: "";
      display: block;
      width: 34px;
      height: 3px;
      background: #FFD700;
      margin-top: 8px;
      border-radius: 2px;
}

.new-footer .footer-brand {
      color: #ffffff;
      font-weight: 800;
      font-size: 1.55rem;
      line-height: 1.2;
      margin-bottom: 16px;
}

.new-footer .footer-about-text {
      color: #B7B9D6;
      font-size: 0.92rem;
      line-height: 1.75;
      margin: 0;
      max-width: 390px;
}

.new-footer .footer-readmore {
      color: #FFD700;
      font-weight: 700;
      text-decoration: none;
      margin-left: 4px;
      white-space: nowrap;
}

.new-footer .footer-readmore:hover {
      text-decoration: underline;
}

.new-footer .footer-link-list {
      list-style: none;
      padding: 0;
      margin: 0;
}

.new-footer .footer-link-list li {
      margin-bottom: 13px;
}

.new-footer .footer-link-list a {
      color: #B7B9D6;
      text-decoration: none;
      font-size: 0.91rem;
      transition: color 0.2s ease, padding-left 0.2s ease;
}

.new-footer .footer-link-list a::before {
      content: "\2192";
      margin-right: 8px;
      color: #FFD700;
}

.new-footer .footer-link-list a:hover {
      color: #FFD700;
      padding-left: 4px;
}

.new-footer .footer-contact-list {
      list-style: none;
      padding: 0;
      margin: 0;
}

.new-footer .footer-contact-list li {
      margin-bottom: 12px;
}

.new-footer .footer-contact-list a,
.new-footer .footer-contact-list p {
      color: #B7B9D6;
      text-decoration: none;
      font-size: 0.9rem;
      margin: 0;
      line-height: 1.55;
}

.new-footer .footer-contact-list a:hover,
.new-footer .footer-location a:hover {
      color: #FFD700;
}

.new-footer .footer-contact-label {
      color: #ffffff;
      font-weight: 700;
      font-size: 0.88rem;
      display: block;
      margin-bottom: 2px;
}

.new-footer .footer-location {
      margin-top: 17px;
}

.new-footer .footer-location-name {
      color: #ffffff;
      font-weight: 700;
      font-size: 0.9rem;
      display: block;
      margin-bottom: 3px;
}

.new-footer .footer-location a {
      color: #B7B9D6;
      font-size: 0.84rem;
      text-decoration: none;
}

.new-footer .footer-social {
      margin-top: 22px;
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
}

.new-footer .footer-social a.social-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.08);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #ffffff !important;
      margin: 0 !important;
      transition: background 0.25s ease, color 0.25s ease, transform 0.25s ease;
      border: 1px solid rgba(246, 190, 1, 0.55);
      text-decoration: none;
}

.new-footer .footer-social a.social-icon:hover {
      background: #FFD700;
      color: #14163a !important;
      transform: translateY(-2px);
}

.footer-bottom-bar {
      background: #0d0e28;
      padding: 16px 0;
      border-top: 1px solid rgba(255, 215, 0, 0.12);
}

.footer-bottom-bar .footer-bottom-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
}

.footer-bottom-bar small {
      color: #B7B9D6;
      font-size: 0.8rem;
}

.footer-bottom-bar a.nav-item {
      color: #FFD700 !important;
      text-decoration: none;
}

.footer-bottom-bar .footer-bottom-links {
      color: #B7B9D6;
      font-size: 0.8rem;
      white-space: nowrap;
}

.footer-bottom-bar .footer-bottom-links a {
      color: #B7B9D6;
      text-decoration: none;
}

.footer-bottom-bar .footer-bottom-links a:hover {
      color: #FFD700;
}

@media (max-width: 991px) {
      .new-footer .footer-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 36px 30px;
      }
}

@media (max-width: 575px) {
      .new-footer {
            padding: 40px 0 20px;
      }

      .new-footer .footer-grid {
            grid-template-columns: 1fr;
            gap: 30px;
      }

      .new-footer .footer-brand {
            font-size: 1.4rem;
      }

      .footer-bottom-bar .footer-bottom-inner {
            flex-direction: column;
            align-items: flex-start;
      }

      .footer-bottom-bar .footer-bottom-links {
            white-space: normal;
      }
}

/* A11Y-10: Footer link target size */
footer a {
      min-height: 24px;
      display: inline-flex;
      align-items: center;
}
</style>

<footer class="new-footer container-fluid">
      <div class="container footer-inner">
            <div class="footer-grid">

                  <!-- COMPANY / ABOUT -->
                  <div class="footer-col">
                        <div class="footer-brand">DharwadHubballiTutor</div>
                        <?php
                        $aboutFull = strip_tags($business->getBusinessAboutBusiness());
                        $aboutShort = $aboutFull;
                        if (strlen($aboutFull) > 180) {
                              $cut = substr($aboutFull, 0, 180);
                              $endPoint = strrpos($cut, ' ');
                              $aboutShort = $endPoint ? substr($cut, 0, $endPoint) : $cut;
                              $aboutShort .= '...';
                        }
                        ?>
                        <p class="footer-about-text">
                              <?php echo $aboutShort; ?>
                              <?php if (strlen($aboutFull) > 180): ?>
                                    <a href="javascript:void(0);" class="footer-readmore" data-bs-toggle="modal" data-bs-target="#aboutModal">Read More</a>
                              <?php endif; ?>
                        </p>

                        <div class="footer-social">
                              <?php
                              foreach ($socialMediaHandles as $handle) {
                                    echo '<a class="social-icon" href="' . $handle->getHandle() . '" target="_blank" rel="noopener">' . $handle->getIcon() . '</a>';
                              }
                              ?>
                              <a class="social-icon"
                                    href="https://www.youtube.com/@dharwadhubballitutor"
                                    target="_blank"
                                    rel="noopener"
                                    aria-label="YouTube">
                                    <i class="fa fa-youtube-play"></i>
                              </a>
                        </div>

                        <meta name="keywords" content="NASSCOM member training institute, MSME registered institute, ISO 9001:2015 certified training center in Dharwad Hubballi">
                  </div>

                  <!-- QUICK LINKS -->
                  <div class="footer-col">
                        <h5>Quick Links</h5>
                        <ul class="footer-link-list">
                              <li><a href="/">Home</a></li>
                              <li><a href="/about/">About</a></li>
                              <li><a href="/contact/">Contact</a></li>
                        </ul>
                  </div>

                  <!-- USEFUL LINKS -->
                  <div class="footer-col">
                        <h5>Useful Links</h5>
                        <ul class="footer-link-list">
                              <li><a href="/termsandconditions/">Terms and Conditions</a></li>
                              <li><a href="/PrivacyPolicy/">Privacy Policy</a></li>
                        </ul>
                  </div>

                  <!-- CONTACT & LOCATIONS -->
                  <div class="footer-col">
                        <h5>Contact &amp; Locations</h5>

                        <ul class="footer-contact-list">
                              <li>
                                    <a href="tel:<?php echo $business->getBusinessContact(); ?>">
                                          +<?php echo ltrim($business->getBusinessContact(), '+'); ?>
                                    </a>
                              </li>
                              <li>
                                    <a href="tel:+918007961759">+91 80079 61759</a>
                              </li>
                              <li>
                                    <a href="mailto:<?php echo $business->getBusinessEmail(); ?>">
                                          <?php echo $business->getBusinessEmail(); ?>
                                    </a>
                              </li>
                        </ul>

                        <div class="footer-location">
                              <span class="footer-location-name">Dharwad Branch</span>
                              <a href="https://www.google.com/maps/place/DharwadHubballiTutor(Web+design+and+development,+Digital+Marketing,+Data+Analytics,+Automation+software+Testing)/@15.4368031,74.8262053,11z/data=!4m6!3m5!1s0x3bb8d370eace81bb:0xf20b739d863002a2!8m2!3d15.4367276!4d75.0198304!16s%2Fg%2F11lf_gg9fb?entry=ttu&amp;g_ep=EgoyMDI2MDgyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" rel="noopener">View on Google Maps</a>
                        </div>

                        <div class="footer-location">
                              <span class="footer-location-name">Hubballi Branch</span>
                              <a href="https://www.google.com/maps/place/Dharwadhubballitutor+(Power+Bi,+Advanced+Excel,Data+analytics,+full+stack+development,+digital+marketing,software+testing,)/@15.3617582,75.1241305,17z/data=!4m6!3m5!1s0x3bb8d7fbf392e209:0x981b8c9c9e8507dc!8m2!3d15.3617582!4d75.1241305!16s%2Fg%2F11s77z7r47?entry=ttu&amp;g_ep=EgoyMDI2MDgyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" rel="noopener">View on Google Maps</a>
                        </div>

                        <div class="footer-location">
                              <span class="footer-location-name">Belagavi Branch</span>
                              <a href="https://www.google.com/maps/place/Deepak's+Spa+Salon/@15.8345276,74.4940868,2906m/data=!3m1!1e3!4m10!1m2!2m1!1sOpposite+to+RPD+College+gate,+above+deepak+saloon,+anjana+woods+building,+Belgavi+590006!3m6!1s0x3bbf668422ad7bb1:0x884c4554d570e8e6!8m2!3d15.8345276!4d74.506832!15sClhPcHBvc2l0ZSB0byBSUEQgQ29sbGVnZ2UgZ2F0ZSBhYm92ZSBkZWVwYWsgc2Fsb29uIGFuamFuYSB3b29kcyBidWlsZGluZyBCZWxnYXZpIDU5MDAwNloiVW9wcG9zaXRlIHRvIHJwZCBjb2xsZWdlIGdhdGUgYWJvdmUgZGVlcGFrIHNhbG9vbiBhbmphbmEgd29vZHMgYnVpbGRpbmcgYmVsZ2F2aSA1OTAwMDaSAQxiZWF1dHlfc2Fsb26aASNDaFpEU1VoTk1HOW5TMFZKUTBGblRVTm5lR1Z5V2xCbkVBReABAPoBBAgAEEo!16s%2Fg%2F11c719ys81?entry=ttu&amp;g_ep=EgoyMDI2MDgyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" rel="noopener">View on Google Maps</a>
                        </div>
                  </div>
            </div>
      </div>
</footer>

<style>
      #aboutModal .modal-content {
            background: #ffffff !important;
            border-radius: 20px;
            border: none;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(20, 15, 60, 0.35);
      }

      #aboutModal .modal-header {
            background: #ffffff !important;
            border-bottom: 1px solid #eef0f4;
            padding: 24px 28px;
      }

      #aboutModal .modal-body {
            background: #ffffff !important;
            padding: 24px 28px 30px;
            max-height: 65vh;
            overflow-y: auto;
      }

      #aboutModal .modal-body p {
            color: #333 !important;
      }
</style>

<div class="modal fade" id="aboutModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 style="color:#14163a;font-weight:700;">About DharwadHubballiTutor</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                        <p style="line-height:1.7;color:#333;"><?php echo $aboutFull; ?></p>
                  </div>
                  <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
            </div>
      </div>
</div>
<div class="footer-bottom-bar">
      <div class="container footer-bottom-inner">
            <small>All rights reserved to : <a class="nav-item" href="/">@DharwadHubballitutor</a></small>
            <span class="footer-bottom-links">
                  <a href="/termsandconditions/">Training</a> &bull;
                  <a href="/about/">Projects</a> &bull;
                  <a href="/contact/">Placements</a>
            </span>
      </div>
</div>
<link rel="stylesheet" href="/chatbot/chatbot.css">
<link rel="preload"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
      as="style"
      onload="this.onload=null;this.rel='stylesheet'">

<noscript>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</noscript>
<div class="toast" id="ai-chatbot">
      <div class="toast-header" id="chat-header">
            Ask DharwadHubballiTutor AI
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
      </div>
      <div class="toast-body">
            <div id="chat-body">
                  <div id="typing" style="display:none;font-size:13px;">AI is typing...</div>
            </div>

            <form id="chat-form">

                  <div class="input-group mb-3">
                        <input
                              id="chat-input"
                              type="text"
                              enterkeyhint="send"
                              placeholder=""
                              autocomplete="off"
                              class="form-control" />
                        <button class="btn btn-success m-0" type="submit">Go</button>
                  </div>
            </form>



            <div id="chat-footer">
                  <a href="https://wa.me/919741237334" target="_blank">📲 WhatsApp</a>
                  <a href="tel:+919741237334">📞 Call</a>
            </div>
      </div>
</div>
<button type="button" id="toastbtn" class="chat-fab">
    🤖
</button>
<style>
      #toastbtn {
            position: fixed !important;
            right: 20px !important;
            bottom: 20px !important;

            width: 60px !important;
            height: 60px !important;

            display: flex !important;
            align-items: center !important;
            justify-content: center !important;

            border-radius: 50% !important;
            border: none !important;

            background: #341963 !important;
            color: #fff !important;

            font-size: 26px !important;

            cursor: pointer;
            z-index: 999999 !important;

            box-shadow: 0 10px 25px rgba(0, 0, 0, .35);
            transition: .3s;
      }

      #toastbtn:hover {
            transform: scale(1.08);
            background: #F6BE01 !important;
            color: #14163A !important;
      }

      #toastbtn i {
            color: inherit;
      }

      @media (max-width:768px) {

            #toastbtn {
                  position: fixed !important;

                  left: auto;
                  right: 15px;
                  bottom: 90px;

                  width: 56px;
                  height: 56px;

                  touch-action: none;
                  cursor: grab;

                  z-index: 999999;
            }

            #toastbtn:active {
                  cursor: grabbing;
            }

            #toastbtn {
                  position: fixed;
                  right: 15px;
                  bottom: 90px;
            }

      }

      @media (max-width:768px) {

            #ai-chatbot {

                  position: fixed !important;

                  width: 87% !important;
                  max-width: 87% !important;

                  left: 50% !important;
                  right: auto !important;

                  bottom: 15px !important;

                  transform: translateX(-50%) !important;

                  margin: 0 !important;

                  border-radius: 18px !important;

                  z-index: 999999 !important;
            }

            #chat-body {

                  height: 180px !important;

            }

      }
</style>
<script>
      document.addEventListener("DOMContentLoaded", function() {

            const toastEl = document.getElementById("ai-chatbot");

            const toast = new bootstrap.Toast(toastEl, {
                  autohide: false
            });

            // Button click
            document.getElementById("toastbtn").addEventListener("click", function() {
                  toast.show();
            });

            // Auto-open only on mobile
            if (window.innerWidth <= 768) {
                  setTimeout(function() {
                        toast.show();
                  }, 1000);
            }

      });
</script>
<script>
      document.addEventListener("DOMContentLoaded", function() {

            const form = document.getElementById("chat-form");
            const input = document.getElementById("chat-input");
            const body = document.getElementById("chat-body");

            function appendMsg(sender, text) {
                  const msgWrapper = document.createElement("div");
                  msgWrapper.className = sender === "AI" ? "ai-row" : "user-row";

                  if (sender === "AI") {
                        msgWrapper.innerHTML = `
     

      <div class="ai-msg">
      <img src="/img/favicon.png" class="ai-avatar" alt="AI Logo">
        ${text.replace(/\n/g, "<br>").replace(/�/g, "&bull;")}
      </div>
    `;
                  } else {
                        msgWrapper.innerHTML = `
      <div class="user-msg">
        ${text.replace(/\n/g, "<br>")}
      </div>
    `;
                  }

                  const chatBody = document.getElementById("chat-body");
                  chatBody.appendChild(msgWrapper);
                  chatBody.scrollTop = chatBody.scrollHeight;
            }


            // ✅ FORM SUBMIT — works on desktop + mobile
            form.addEventListener("submit", function(e) {
                  e.preventDefault(); // 🔥 CRITICAL

                  const msg = input.value.trim();
                  if (!msg) return;

                  appendMsg("You", msg);
                  input.value = "";
                  input.disabled = true;

                  fetch("/chatbot/chatbot.php", {
                              method: "POST",
                              headers: {
                                    "Content-Type": "application/json"
                              },
                              body: JSON.stringify({
                                    message: msg
                              })
                        })
                        .then(res => res.json())
                        .then(data => {
                              appendMsg("AI", data.reply);
                        })
                        .catch(() => {
                              appendMsg("AI", "Something went wrong. Please try again.");
                        })
                        .finally(() => {
                              input.disabled = false;
                              input.focus();
                        });
            });

      });
      document.addEventListener("DOMContentLoaded", function() {

            if (window.innerWidth > 768) return;

            const btn = document.getElementById("toastbtn");
            if (!btn) return;

            let dragging = false;
            let moved = false;

            let startX = 0;
            let startY = 0;

            let offsetX = 0;
            let offsetY = 0;



            btn.addEventListener("touchstart", function(e) {

                  const touch = e.touches[0];

                  dragging = true;
                  moved = false;

                  const rect = btn.getBoundingClientRect();

                  offsetX = touch.clientX - rect.left;
                  offsetY = touch.clientY - rect.top;

            }, {
                  passive: true
            });

            btn.addEventListener("touchmove", function(e) {

                  if (!dragging) return;

                  e.preventDefault();

                  moved = true;

                  const touch = e.touches[0];

                  let x = touch.clientX - offsetX;
                  let y = touch.clientY - offsetY;

                  x = Math.max(0, Math.min(window.innerWidth - btn.offsetWidth, x));
                  y = Math.max(0, Math.min(window.innerHeight - btn.offsetHeight, y));

                  btn.style.transition = "none"; // Disable animation while dragging
                  btn.style.left = x + "px";
                  btn.style.top = y + "px";
                  btn.style.right = "auto";
                  btn.style.bottom = "auto";

            }, {
                  passive: false
            });

            btn.addEventListener("touchend", function() {

                  dragging = false;



            });

            btn.addEventListener("click", function(e) {

                  if (moved) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        moved = false;
                  }

            }, true);

      });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.2/jquery.min.js" integrity="sha512-tWHlutFnuG0C6nQRlpvrEhE4QpkG1nn2MOUMWmUeRePl4e3Aki0VB6W1v3oLjFtd0hVOtRQ9PHpSfN6u6/QXkQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
      $(document).ready(function() {
            $('#owl-one').owlCarousel({
                  stagePadding: 50,
                  loop: true,
                  margin: 10,
                  nav: false,
                  responsiveClass: true,
                  autoplay: true,
                  autoplayTimeout: 2500,
                  responsive: {
                        0: {
                              items: 1
                        },
                        600: {
                              items: 2
                        },
                        1000: {
                              items: 4
                        }
                  }
            });
            $('#owl-two').owlCarousel({
                  items: 1,
                  loop: true,
                  margin: 10,
                  autoplay: true,
                  autoplayTimeout: 5000,
                  autoplayHoverPause: true,
                  autoHeight: true,
                  responsive: {
                        0: {
                              items: 1
                        },
                        600: {
                              items: 1
                        },
                        1000: {
                              items: 1
                        }
                  }
            });
            $('#owl-three').owlCarousel({
                  stagePadding: 50,
                  loop: true,
                  margin: 10,
                  nav: false,
                  responsiveClass: true,
                  autoplay: true,
                  autoplayTimeout: 2500,
                  responsive: {
                        0: {
                              items: 1
                        },
                        600: {
                              items: 2
                        },
                        1000: {
                              items: 4
                        }
                  }
            });
            $('.play').on('click', function() {
                  owl.trigger('play.owl.autoplay', [1000])
            })
            $('.stop').on('click', function() {
                  owl.trigger('stop.owl.autoplay')
            })

      })
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>