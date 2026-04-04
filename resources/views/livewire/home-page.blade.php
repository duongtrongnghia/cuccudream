<div>
    {{-- ═══════════════════════════════════════════════════════════════
         NAVIGATION BAR
    ═══════════════════════════════════════════════════════════════ --}}
    <nav style="position:fixed; top:0; left:0; right:0; z-index:50; background:rgba(245,237,224,0.92); backdrop-filter:blur(12px); border-bottom:1px solid #E0D5C5;">
        <div style="max-width:1200px; margin:0 auto; padding:0.75rem 1.5rem; display:flex; align-items:center; justify-content:space-between;">
            <a href="/" style="text-decoration:none;">
                <span style="font-size:1.4rem; font-weight:800; color:#2D2926;">
                    <span style="color:#D4896E;">Cúc Cu</span> <span style="color:#7B8B6F;">Dream</span><span style="font-size:0.7rem;">™</span>
                </span>
            </a>
            <div style="display:flex; align-items:center; gap:1.5rem;">
                <a href="#about" style="text-decoration:none; color:#8B7E74; font-weight:600; font-size:0.85rem; display:none;" class="md-show">Câu chuyện</a>
                <a href="#courses" style="text-decoration:none; color:#8B7E74; font-weight:600; font-size:0.85rem; display:none;" class="md-show">Khóa học</a>
                <a href="#community" style="text-decoration:none; color:#8B7E74; font-weight:600; font-size:0.85rem; display:none;" class="md-show">Cộng đồng</a>
                <a href="#press" style="text-decoration:none; color:#8B7E74; font-weight:600; font-size:0.85rem; display:none;" class="md-show">Báo chí</a>
                <a href="{{ route('login') }}" class="btn" style="background:#D4896E; color:#fff; padding:0.5rem 1.25rem; border-radius:9999px; font-size:0.85rem; font-weight:700; text-decoration:none;">
                    Tham gia ngay
                </a>
            </div>
        </div>
    </nav>

    {{-- ═══════════════════════════════════════════════════════════════
         HERO SECTION
    ═══════════════════════════════════════════════════════════════ --}}
    <section style="min-height:100vh; display:flex; align-items:center; justify-content:center; padding:6rem 1.5rem 4rem; position:relative; overflow:hidden;">
        {{-- Decorative elements --}}
        <div style="position:absolute; top:10%; right:5%; font-size:4rem; opacity:0.12;" class="animate-float">🎨</div>
        <div style="position:absolute; bottom:15%; left:8%; font-size:3rem; opacity:0.1;" class="animate-float delay-2">🦋</div>
        <div style="position:absolute; top:30%; left:5%; font-size:2.5rem; opacity:0.08;" class="animate-float delay-3">✨</div>

        <div style="max-width:800px; text-align:center;">
            <p class="fade-in" style="font-size:1rem; font-weight:600; color:#D4896E; letter-spacing:0.05em; text-transform:uppercase; margin-bottom:1rem;">
                Cuc Cu's Dream Factory
            </p>
            <h1 class="fade-in-up delay-1" style="font-size:clamp(2rem,5vw,3.5rem); font-weight:900; line-height:1.2; color:#2D2926; margin-bottom:1.5rem;">
                Đánh thức <span style="color:#D4896E;">giấc mơ</span> nguyên thuỷ<br>
                qua <span style="color:#7B8B6F;">nghệ thuật</span>
            </h1>
            <p class="fade-in-up delay-2" style="font-size:1.15rem; color:#8B7E74; line-height:1.8; max-width:600px; margin:0 auto 2rem;">
                Nơi trẻ em và người lớn cùng chữa lành, sáng tạo và kể câu chuyện của chính mình qua những nét vẽ giản dị.
                Bởi vì giấc mơ tuổi thơ không bao giờ nên bị khô héo.
            </p>
            <div class="fade-in-up delay-3" style="display:flex; gap:1rem; justify-content:center; flex-wrap:wrap;">
                <a href="{{ route('register') }}" class="btn" style="background:#D4896E; color:#fff; padding:0.75rem 2rem; border-radius:9999px; font-size:1rem; font-weight:700; text-decoration:none; box-shadow:0 4px 15px rgba(212,137,110,0.3);">
                    Bắt đầu hành trình
                </a>
                <a href="#about" class="btn" style="background:#FFFCF7; color:#2D2926; padding:0.75rem 2rem; border-radius:9999px; font-size:1rem; font-weight:600; text-decoration:none; border:1px solid #E0D5C5;">
                    Khám phá thêm
                </a>
            </div>

            {{-- Stats --}}
            <div class="fade-in-up delay-4" style="display:flex; gap:2.5rem; justify-content:center; margin-top:3rem; flex-wrap:wrap;">
                <div style="text-align:center;">
                    <div style="font-size:1.8rem; font-weight:900; color:#D4896E;">15,000+</div>
                    <div style="font-size:0.8rem; color:#8B7E74; font-weight:600;">Người theo dõi</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.8rem; font-weight:900; color:#7B8B6F;">10+</div>
                    <div style="font-size:0.8rem; color:#8B7E74; font-weight:600;">Năm gieo mầm sáng tạo</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.8rem; font-weight:900; color:#C9A84C;">1,000+</div>
                    <div style="font-size:0.8rem; color:#8B7E74; font-weight:600;">Học viên đã tham gia</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         FOUNDER STORY
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="about" style="padding:5rem 1.5rem; background:#FFFCF7;">
        <div style="max-width:900px; margin:0 auto;">
            <div style="text-align:center; margin-bottom:3rem;">
                <p style="font-size:0.85rem; font-weight:700; color:#D4896E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.5rem;">Câu chuyện của chúng mình</p>
                <h2 style="font-size:clamp(1.5rem,3.5vw,2.2rem); font-weight:800; color:#2D2926;">
                    Từ tro tàn tuổi 30, một giấc mơ hồi sinh
                </h2>
            </div>

            <div style="display:grid; gap:2rem; grid-template-columns:1fr;" class="story-grid">
                {{-- Story card 1 --}}
                <div class="card" style="padding:2rem; border-left:3px solid #D4896E;">
                    <p style="font-size:1.05rem; line-height:1.9; color:#3D3632;">
                        Năm chuyển từ 29 sang 30 là một năm sụp đổ toàn diện — mối quan hệ 7 năm tan vỡ,
                        startup giáo dục sụp đổ, thua sạch cả tình lẫn tiền.
                        Cuộc sống như chạm đáy, mọi thứ đều u ám.
                    </p>
                    <p style="font-size:1.05rem; line-height:1.9; color:#3D3632; margin-top:1rem;">
                        Chỉ trừ một điều vẫn còn rực sáng:
                        <strong style="color:#D4896E;">niềm đam mê với nghệ thuật và trái tim rung cảm trước sự ngây thơ của trẻ em.</strong>
                    </p>
                </div>

                {{-- Story card 2 --}}
                <div class="card" style="padding:2rem; border-left:3px solid #7B8B6F;">
                    <p style="font-size:1.05rem; line-height:1.9; color:#3D3632;">
                        Thời bé, tôi đã từng có một ước mơ cháy bỏng: tạo ra một thế giới nơi tuổi thơ và trí sáng tạo được sống mãi mãi như Neverland của Peter Pan.
                    </p>
                    <p style="font-size:1.05rem; line-height:1.9; color:#3D3632; margin-top:1rem;">
                        Và rồi tôi quyết định tạo ra một không gian chỉ của riêng mình —
                        <strong style="color:#7B8B6F;">Cuc Cu's Dream Factory</strong>.
                        Khi tôi cho phép bản thân mình được chân thật và yếu đuối,
                        điều kỳ diệu đã xảy ra: tôi nhận được sự vỗ về, bao dung từ những người xa lạ.
                    </p>
                </div>

                {{-- Mission statement --}}
                <div style="background:linear-gradient(135deg, #F5E0D5, #E5EBE0); border-radius:0.75rem; padding:2rem; text-align:center;">
                    <p style="font-size:1.2rem; font-weight:700; line-height:1.8; color:#2D2926;">
                        "Giúp người khác làm bạn với chính mình<br>
                        & nhớ lại mơ ước ban sơ qua hình vẽ."
                    </p>
                    <p style="font-size:0.9rem; color:#8B7E74; margin-top:0.75rem; font-weight:600;">— Cúc Cu Nguyễn, Founder</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         MISSION / PHILOSOPHY
    ═══════════════════════════════════════════════════════════════ --}}
    <section style="padding:5rem 1.5rem; background:#F5EDE0;">
        <div style="max-width:1000px; margin:0 auto;">
            <div style="text-align:center; margin-bottom:3rem;">
                <p style="font-size:0.85rem; font-weight:700; color:#7B8B6F; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.5rem;">Triết lý</p>
                <h2 style="font-size:clamp(1.5rem,3.5vw,2.2rem); font-weight:800; color:#2D2926;">
                    Vẽ không dành riêng cho vài người
                </h2>
                <p style="font-size:1.05rem; color:#8B7E74; margin-top:0.75rem; max-width:600px; margin-left:auto; margin-right:auto;">
                    Vẽ không phải để người khác ngắm. Vẽ là cách bạn nói chuyện với những phiên bản khác nhau của chính mình.
                </p>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem;">
                {{-- Pillar 1 --}}
                <div class="card" style="padding:1.75rem; text-align:center;">
                    <div style="font-size:2.5rem; margin-bottom:0.75rem;">🎨</div>
                    <h3 style="font-size:1.1rem; font-weight:800; color:#2D2926; margin-bottom:0.5rem;">Sáng tạo không giới hạn</h3>
                    <p style="font-size:0.95rem; color:#8B7E74; line-height:1.7;">
                        Không cần năng khiếu bẩm sinh. Không cần nét vẽ cầu kỳ.
                        Chỉ cần bạn dám cầm bút lên và cho phép mình được tự do.
                    </p>
                </div>

                {{-- Pillar 2 --}}
                <div class="card" style="padding:1.75rem; text-align:center;">
                    <div style="font-size:2.5rem; margin-bottom:0.75rem;">📖</div>
                    <h3 style="font-size:1.1rem; font-weight:800; color:#2D2926; margin-bottom:0.5rem;">Kể chuyện qua tranh</h3>
                    <p style="font-size:0.95rem; color:#8B7E74; line-height:1.7;">
                        Mỗi lớp học bắt đầu bằng một câu chuyện đầy cảm hứng,
                        khơi gợi trí tưởng tượng rồi vẽ lại câu chuyện của riêng mình.
                    </p>
                </div>

                {{-- Pillar 3 --}}
                <div class="card" style="padding:1.75rem; text-align:center;">
                    <div style="font-size:2.5rem; margin-bottom:0.75rem;">💚</div>
                    <h3 style="font-size:1.1rem; font-weight:800; color:#2D2926; margin-bottom:0.5rem;">Chữa lành qua nghệ thuật</h3>
                    <p style="font-size:0.95rem; color:#8B7E74; line-height:1.7;">
                        Một không gian an toàn để mọi người, từ trẻ nhỏ đến người lớn,
                        nuôi dưỡng cảm xúc và tâm trí thông qua nghệ thuật.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         COURSES
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="courses" style="padding:5rem 1.5rem; background:#FFFCF7;">
        <div style="max-width:1000px; margin:0 auto;">
            <div style="text-align:center; margin-bottom:3rem;">
                <p style="font-size:0.85rem; font-weight:700; color:#C9A84C; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.5rem;">Khóa học</p>
                <h2 style="font-size:clamp(1.5rem,3.5vw,2.2rem); font-weight:800; color:#2D2926;">
                    Chơi vui, vẽ đẹp, kể chuyện hay
                </h2>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem;">
                {{-- Course 1: Dare To Draw --}}
                <div class="card" style="padding:0; overflow:hidden;">
                    <div style="background:linear-gradient(135deg, #F5E0D5, #FBE8E2); padding:1.5rem; text-align:center;">
                        <div style="font-size:2rem;">✏️</div>
                        <h3 style="font-size:1.15rem; font-weight:800; color:#2D2926; margin-top:0.5rem;">Dare To Draw</h3>
                        <p style="font-size:0.8rem; color:#D4896E; font-weight:700;">Dám Vẽ Cho Mình</p>
                    </div>
                    <div style="padding:1.25rem;">
                        <p style="font-size:0.9rem; color:#8B7E74; line-height:1.7; margin-bottom:1rem;">
                            Khóa học 10 buổi giúp bạn tháo bỏ định kiến "vẽ phải có năng khiếu".
                            Từ người chưa bao giờ cầm bút đến tự tin vẽ nên thế giới của riêng mình.
                        </p>
                        <div style="display:flex; flex-wrap:wrap; gap:0.5rem;">
                            <span class="badge" style="background:#F5E0D5; color:#B8725A;">10 buổi</span>
                            <span class="badge" style="background:#E5EBE0; color:#5A6B4E;">Người lớn</span>
                            <span class="badge" style="background:#F5EEDA; color:#8B7B2A;">Online & Offline</span>
                        </div>
                    </div>
                </div>

                {{-- Course 2: Chibi --}}
                <div class="card" style="padding:0; overflow:hidden;">
                    <div style="background:linear-gradient(135deg, #E5EBE0, #D5E8D5); padding:1.5rem; text-align:center;">
                        <div style="font-size:2rem;">🎀</div>
                        <h3 style="font-size:1.15rem; font-weight:800; color:#2D2926; margin-top:0.5rem;">Vẽ Chibi</h3>
                        <p style="font-size:0.8rem; color:#7B8B6F; font-weight:700;">Tối giản & Đáng yêu</p>
                    </div>
                    <div style="padding:1.25rem;">
                        <p style="font-size:0.9rem; color:#8B7E74; line-height:1.7; margin-bottom:1rem;">
                            Chỉ sau 4 buổi, bạn có thể vẽ người, vẽ động vật theo phong cách Chibi.
                            Cuối khóa tự sáng tác nhân vật Chibi của riêng mình.
                        </p>
                        <div style="display:flex; flex-wrap:wrap; gap:0.5rem;">
                            <span class="badge" style="background:#E5EBE0; color:#5A6B4E;">4 buổi</span>
                            <span class="badge" style="background:#F5E0D5; color:#B8725A;">Mọi lứa tuổi</span>
                        </div>
                    </div>
                </div>

                {{-- Course 3: Họa sĩ Cá tính & Truyện tranh --}}
                <div class="card" style="padding:0; overflow:hidden;">
                    <div style="background:linear-gradient(135deg, #F5EEDA, #FDE8D8); padding:1.5rem; text-align:center;">
                        <div style="font-size:2rem;">🌈</div>
                        <h3 style="font-size:1.15rem; font-weight:800; color:#2D2926; margin-top:0.5rem;">Họa sĩ Cá tính</h3>
                        <p style="font-size:0.8rem; color:#C9A84C; font-weight:700;">Sáng tác Truyện tranh</p>
                    </div>
                    <div style="padding:1.25rem;">
                        <p style="font-size:0.9rem; color:#8B7E74; line-height:1.7; margin-bottom:1rem;">
                            Cùng thầy Hà Mã khám phá phong cách riêng, sáng tạo thế giới truyện tranh
                            của em. Nơi sự hồn nhiên gặp gỡ những sắc màu.
                        </p>
                        <div style="display:flex; flex-wrap:wrap; gap:0.5rem;">
                            <span class="badge" style="background:#F5EEDA; color:#8B7B2A;">Trẻ em</span>
                            <span class="badge" style="background:#E5EBE0; color:#5A6B4E;">Sáng tạo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         COMMUNITY VOICES / TESTIMONIALS
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="community" style="padding:5rem 1.5rem; background:#F5EDE0;">
        <div style="max-width:1000px; margin:0 auto;">
            <div style="text-align:center; margin-bottom:3rem;">
                <p style="font-size:0.85rem; font-weight:700; color:#D4896E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.5rem;">Cộng đồng</p>
                <h2 style="font-size:clamp(1.5rem,3.5vw,2.2rem); font-weight:800; color:#2D2926;">
                    Những giấc mơ được vẽ nên
                </h2>
                <p style="font-size:1.05rem; color:#8B7E74; margin-top:0.75rem;">
                    Từ cuộc thi vẽ online đến những tác phẩm chữa lành — mỗi bức tranh là một câu chuyện.
                </p>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.5rem;">
                {{-- Testimonial 1: Kim Ngọc --}}
                <div class="card" style="padding:1.75rem;">
                    <div style="font-size:2rem; color:#D4896E; margin-bottom:0.5rem;">"</div>
                    <p style="font-size:0.95rem; color:#3D3632; line-height:1.8; font-style:italic;">
                        Trước đây, mình không vẽ vì mình sợ vẽ xấu.
                        Sau nhiều ngày cầm bút lên vẽ và chìm đắm vào trang giấy,
                        mình dần dần tiến bộ và tìm thấy sự bình yên trong tâm hồn mình.
                    </p>
                    <div style="margin-top:1rem; display:flex; align-items:center; gap:0.75rem;">
                        <div style="width:36px; height:36px; border-radius:50%; background:#F5E0D5; display:flex; align-items:center; justify-content:center; font-weight:800; color:#D4896E; font-size:0.85rem;">KN</div>
                        <div>
                            <div style="font-weight:700; font-size:0.85rem; color:#2D2926;">Kim Ngọc</div>
                            <div style="font-size:0.75rem; color:#8B7E74;">Học viên Dare To Draw</div>
                        </div>
                    </div>
                </div>

                {{-- Testimonial 2: Cẩm Vân --}}
                <div class="card" style="padding:1.75rem;">
                    <div style="font-size:2rem; color:#7B8B6F; margin-bottom:0.5rem;">"</div>
                    <p style="font-size:0.95rem; color:#3D3632; line-height:1.8; font-style:italic;">
                        Dù trưởng thành và đi xa đến đâu, gia đình vẫn luôn là điểm tựa vững chãi.
                        Với tôi, em chính là ngôi nhà ấm áp nhất.
                        Mình đã vẽ nên câu chuyện "Chị và em: Mùa xuân nhỏ trong lòng nhau".
                    </p>
                    <div style="margin-top:1rem; display:flex; align-items:center; gap:0.75rem;">
                        <div style="width:36px; height:36px; border-radius:50%; background:#E5EBE0; display:flex; align-items:center; justify-content:center; font-weight:800; color:#7B8B6F; font-size:0.85rem;">CV</div>
                        <div>
                            <div style="font-weight:700; font-size:0.85rem; color:#2D2926;">Cẩm Vân</div>
                            <div style="font-size:0.75rem; color:#8B7E74;">Tác giả cuộc thi vẽ</div>
                        </div>
                    </div>
                </div>

                {{-- Testimonial 3: Nguyễn Minh --}}
                <div class="card" style="padding:1.75rem;">
                    <div style="font-size:2rem; color:#C9A84C; margin-bottom:0.5rem;">"</div>
                    <p style="font-size:0.95rem; color:#3D3632; line-height:1.8; font-style:italic;">
                        Với em, yêu không phải là một khoảnh khắc lãng mạn,
                        mà là khi ta học cách tha thứ, chữa lành và cho nhau cơ hội được sống đúng với chính mình.
                        Đây là hành trình chữa lành sau 12 năm.
                    </p>
                    <div style="margin-top:1rem; display:flex; align-items:center; gap:0.75rem;">
                        <div style="width:36px; height:36px; border-radius:50%; background:#F5EEDA; display:flex; align-items:center; justify-content:center; font-weight:800; color:#C9A84C; font-size:0.85rem;">NM</div>
                        <div>
                            <div style="font-weight:700; font-size:0.85rem; color:#2D2926;">Nguyễn Minh</div>
                            <div style="font-size:0.75rem; color:#8B7E74;">Tác giả cuộc thi vẽ</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Community highlight --}}
            <div style="margin-top:2.5rem; background:linear-gradient(135deg, #F5E0D5, #E5EBE0); border-radius:0.75rem; padding:2rem; text-align:center;">
                <p style="font-size:1.05rem; color:#3D3632; line-height:1.8; max-width:650px; margin:0 auto;">
                    Từ cuộc thi vẽ về tình yêu, đến những bức tranh cho nắng, cho mèo, cho người bạn thân —
                    cộng đồng CCDF đang cùng nhau
                    <strong style="color:#D4896E;">can đảm vẽ nên thế giới của riêng mình.</strong>
                </p>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         CHARITY / SOCIAL IMPACT
    ═══════════════════════════════════════════════════════════════ --}}
    <section style="padding:5rem 1.5rem; background:#FFFCF7;">
        <div style="max-width:900px; margin:0 auto;">
            <div style="text-align:center; margin-bottom:3rem;">
                <p style="font-size:0.85rem; font-weight:700; color:#E8A08B; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.5rem;">Thiện nguyện</p>
                <h2 style="font-size:clamp(1.5rem,3.5vw,2.2rem); font-weight:800; color:#2D2926;">
                    Thương nhiều, sẻ chia bằng sắc màu
                </h2>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem;">
                <div class="card" style="padding:1.75rem; text-align:center;">
                    <div style="font-size:2rem; margin-bottom:0.75rem;">🌙</div>
                    <h3 style="font-size:1rem; font-weight:800; color:#2D2926; margin-bottom:0.5rem;">Giải Cứu Mặt Trăng</h3>
                    <p style="font-size:0.9rem; color:#8B7E74; line-height:1.7;">
                        Đón Trăng Rằm bên giường bệnh cùng các bé tại Bệnh viện Nhi Đồng 2,
                        kết hợp cùng Quỹ Mặt Trời.
                    </p>
                </div>

                <div class="card" style="padding:1.75rem; text-align:center;">
                    <div style="font-size:2rem; margin-bottom:0.75rem;">🎄</div>
                    <h3 style="font-size:1rem; font-weight:800; color:#2D2926; margin-bottom:0.5rem;">Thế Giới Giáng Sinh</h3>
                    <p style="font-size:0.9rem; color:#8B7E74; line-height:1.7;">
                        Workshop làm quà tặng bệnh nhi từ ly giấy, sưởi ấm tình người mùa Noel.
                        Phóng sự trên HTV7.
                    </p>
                </div>

                <div class="card" style="padding:1.75rem; text-align:center;">
                    <div style="font-size:2rem; margin-bottom:0.75rem;">📚</div>
                    <h3 style="font-size:1rem; font-weight:800; color:#2D2926; margin-bottom:0.5rem;">Bánh Ngọt Cho Tâm Hồn</h3>
                    <p style="font-size:0.9rem; color:#8B7E74; line-height:1.7;">
                        Bộ sách do cô Cúc Cu làm tác giả, được báo Nhi Đồng Xuân giới thiệu.
                        Ra mắt trên HTV7.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         PRESS / SOCIAL PROOF
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="press" style="padding:5rem 1.5rem; background:#F5EDE0;">
        <div style="max-width:900px; margin:0 auto; text-align:center;">
            <p style="font-size:0.85rem; font-weight:700; color:#8B7E74; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.5rem;">Báo chí & Truyền hình</p>
            <h2 style="font-size:clamp(1.5rem,3.5vw,2.2rem); font-weight:800; color:#2D2926; margin-bottom:2.5rem;">
                Được nhắc tới bởi
            </h2>

            <div style="display:flex; flex-wrap:wrap; justify-content:center; align-items:center; gap:2rem;">
                <div style="background:#FFFCF7; border:1px solid #E0D5C5; border-radius:0.75rem; padding:1rem 1.5rem; font-weight:800; color:#2D2926; font-size:1rem;">
                    📰 Báo Tuổi Trẻ
                </div>
                <div style="background:#FFFCF7; border:1px solid #E0D5C5; border-radius:0.75rem; padding:1rem 1.5rem; font-weight:800; color:#2D2926; font-size:1rem;">
                    📺 HTV7
                </div>
                <div style="background:#FFFCF7; border:1px solid #E0D5C5; border-radius:0.75rem; padding:1rem 1.5rem; font-weight:800; color:#2D2926; font-size:1rem;">
                    🔬 Khoa Học & Phát Triển
                </div>
                <div style="background:#FFFCF7; border:1px solid #E0D5C5; border-radius:0.75rem; padding:1rem 1.5rem; font-weight:800; color:#2D2926; font-size:1rem;">
                    📖 Báo Nhi Đồng
                </div>
            </div>

            <p style="font-size:0.95rem; color:#8B7E74; margin-top:2rem; line-height:1.7; max-width:600px; margin-left:auto; margin-right:auto;">
                Hơn 10 năm gieo mầm sáng tạo cho trẻ em, từ trẻ đặc biệt đến các bệnh nhi.
                Câu chuyện của CCDF đã được báo Tuổi Trẻ, HTV7 và nhiều kênh truyền thông đưa tin.
            </p>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         MASCOT / FUN SECTION
    ═══════════════════════════════════════════════════════════════ --}}
    <section style="padding:4rem 1.5rem; background:#FFFCF7;">
        <div style="max-width:700px; margin:0 auto; text-align:center;">
            <div style="font-size:3.5rem; margin-bottom:1rem;" class="animate-float">🐷</div>
            <h2 style="font-size:1.5rem; font-weight:800; color:#2D2926; margin-bottom:0.75rem;">
                Gặp gỡ Chủ tịch Heo Thỏ
            </h2>
            <p style="font-size:1.05rem; color:#8B7E74; line-height:1.8; max-width:550px; margin:0 auto;">
                Mascot đáng yêu của CCDF — muốn được ăn ngày ba bữa căng bụng,
                ngủ 10 tiếng tròn giấc, gặp gỡ mấy bạn Heo Thỏ dễ thương khác,
                và được vẽ tranh ca hát nhảy múa mỗi ngày!
            </p>
            <p style="font-size:0.85rem; color:#D4896E; font-weight:700; margin-top:1rem; font-style:italic;">
                "Đang tuổi ăn tuổi lớn mà hỏi câu gì khó trả lời ghê á ụt ụt" 🎀
            </p>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         CTA / FINAL
    ═══════════════════════════════════════════════════════════════ --}}
    <section style="padding:5rem 1.5rem; background:linear-gradient(135deg, #F5E0D5, #E5EBE0);">
        <div style="max-width:700px; margin:0 auto; text-align:center;">
            <h2 style="font-size:clamp(1.5rem,4vw,2.5rem); font-weight:900; color:#2D2926; line-height:1.3; margin-bottom:1rem;">
                Bạn đã sẵn sàng<br>vẽ lại giấc mơ của mình chưa?
            </h2>
            <p style="font-size:1.1rem; color:#3D3632; line-height:1.8; margin-bottom:2rem;">
                Cùng nhau, chúng ta sẽ cho phép mình được sống thiện lành như trẻ nhỏ,
                không cần phải xù lông nhím hay hắc hóa để sinh tồn.
            </p>
            <div style="display:flex; gap:1rem; justify-content:center; flex-wrap:wrap;">
                <a href="{{ route('register') }}" class="btn" style="background:#D4896E; color:#fff; padding:0.875rem 2.5rem; border-radius:9999px; font-size:1.1rem; font-weight:800; text-decoration:none; box-shadow:0 4px 20px rgba(212,137,110,0.35);">
                    Tham gia Cúc Cu Dream
                </a>
                <a href="{{ route('login') }}" class="btn" style="background:transparent; color:#2D2926; padding:0.875rem 2.5rem; border-radius:9999px; font-size:1.1rem; font-weight:700; text-decoration:none; border:2px solid #2D2926;">
                    Đăng nhập
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════════════════════════ --}}
    <footer style="padding:2.5rem 1.5rem; background:#2D2926; color:#E0D5C5;">
        <div style="max-width:1000px; margin:0 auto; display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:1.5rem;">
            <div>
                <span style="font-size:1.2rem; font-weight:800;">
                    <span style="color:#D4896E;">Cúc Cu</span> <span style="color:#7B8B6F;">Dream</span><span style="font-size:0.65rem; color:#8B7E74;">™</span>
                </span>
                <p style="font-size:0.8rem; color:#8B7E74; margin-top:0.25rem;">Đánh thức giấc mơ nguyên thuỷ qua nghệ thuật</p>
            </div>
            <div style="display:flex; gap:1.5rem; align-items:center;">
                <a href="{{ route('login') }}" style="color:#E0D5C5; font-size:0.85rem; font-weight:600; text-decoration:none;">Đăng nhập</a>
                <a href="{{ route('register') }}" style="color:#E0D5C5; font-size:0.85rem; font-weight:600; text-decoration:none;">Đăng ký</a>
            </div>
        </div>
        <div style="max-width:1000px; margin:1.5rem auto 0; border-top:1px solid rgba(255,255,255,0.1); padding-top:1rem;">
            <p style="font-size:0.75rem; color:#8B7E74; text-align:center;">
                © {{ date('Y') }} Cuc Cu's Dream Factory. Khai sinh 01/01/2024.
            </p>
        </div>
    </footer>

    {{-- Responsive nav links --}}
    <style>
        @media (min-width: 768px) { .md-show { display: inline !important; } }
    </style>
</div>
