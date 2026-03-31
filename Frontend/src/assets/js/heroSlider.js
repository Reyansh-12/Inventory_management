const normalize = (value) => value?.toLowerCase().replace(/\s+/g, "");

  const categories = [...new Set(products.map((p) => p.category))].slice(0, 6);

  const scrollAmount = 240;

  const handleNext = () => {
    scrollRef.current?.scrollBy({
      left: scrollAmount,
      behavior: "smooth",
    });
  };

  const handlePrev = () => {
    scrollRef.current?.scrollBy({
      left: -scrollAmount,
      behavior: "smooth",
    });
  };

  const handleCategoryChange = (category, index) => {
    setActiveCategory(category);

    const el = categoryRefs.current[index];

    gsap.to(underlineRef.current, {
      x: el.offsetLeft,
      width: el.offsetWidth,
      duration: 0.4,
      ease: "power3.out",
    });
  };
  const buttonRef = useRef(null);

  useEffect(() => {
    const button = buttonRef.current;
    const flair = button.querySelector(".button__flair");

    const xSet = gsap.quickSetter(flair, "xPercent");
    const ySet = gsap.quickSetter(flair, "yPercent");

    const getXY = (e) => {
      const { left, top, width, height } = button.getBoundingClientRect();

      const x = gsap.utils.clamp(
        0,
        100,
        gsap.utils.mapRange(0, width, 0, 100, e.clientX - left),
      );

      const y = gsap.utils.clamp(
        0,
        100,
        gsap.utils.mapRange(0, height, 0, 100, e.clientY - top),
      );

      return { x, y };
    };

    const onEnter = (e) => {
      const { x, y } = getXY(e);
      xSet(x);
      ySet(y);

      gsap.to(flair, {
        scale: 1,
        duration: 0.4,
        ease: "power2.out",
      });
    };

    const onLeave = (e) => {
      const { x, y } = getXY(e);

      gsap.killTweensOf(flair);

      gsap.to(flair, {
        xPercent: x > 90 ? x + 20 : x < 10 ? x - 20 : x,
        yPercent: y > 90 ? y + 20 : y < 10 ? y - 20 : y,
        scale: 0,
        duration: 0.3,
        ease: "power2.out",
      });
    };

    const onMove = (e) => {
      const { x, y } = getXY(e);

      gsap.to(flair, {
        xPercent: x,
        yPercent: y,
        duration: 0.4,
        ease: "power2",
      });
    };

    button.addEventListener("mouseenter", onEnter);
    button.addEventListener("mouseleave", onLeave);
    button.addEventListener("mousemove", onMove);

    return () => {
      button.removeEventListener("mouseenter", onEnter);
      button.removeEventListener("mouseleave", onLeave);
      button.removeEventListener("mousemove", onMove);
    };
  }, []);
  
  useEffect(() => {
    cardRefs.current.forEach((card) => {
      if (!card) return;

      const glare = card.querySelector(".card-glare");
      let bounds;
      let lastShadow = { x: 0, y: 0, blur: 20 };

      const move = (e) => {
        const mouseX = e.clientX;
        const mouseY = e.clientY;

        const leftX = mouseX - bounds.left;
        const topY = mouseY - bounds.top;

        const center = {
          x: leftX - bounds.width / 2,
          y: topY - bounds.height / 2,
        };

        const distance = Math.sqrt(center.x ** 2 + center.y ** 2);

        const rotX = center.y / 40;
        const rotY = -center.x / 40;

        const shadowX = -rotY * 5;
        const shadowY = rotX * 5;
        const shadowBlur = 20 + distance / 120;

        lastShadow = { x: shadowX, y: shadowY, blur: shadowBlur };

        gsap.to(card, {
          rotationX: rotX,
          rotationY: rotY,
          scale: 1.08,
          transformPerspective: 800,
          boxShadow: `${shadowX}px ${shadowY}px ${shadowBlur}px rgba(232,90,138,0.35)`,
          ease: "power2.out",
          duration: 0.3,
        });

        gsap.to(glare, {
          autoAlpha: 1,
          backgroundImage: `radial-gradient(circle at ${center.x * 2 + bounds.width / 2
            }px ${center.y * 2 + bounds.height / 2}px,
            rgba(255,255,255,0.4),
            rgba(255,255,255,0)
          )`,
        });
      };

      const enter = () => {
        bounds = card.getBoundingClientRect();
        document.addEventListener("mousemove", move);
      };

      const leave = () => {
        document.removeEventListener("mousemove", move);

        gsap.to(card, {
          rotationX: 0,
          rotationY: 0,
          scale: 1,
          boxShadow: `0px 0px ${lastShadow.blur}px rgba(0,0,0,0)`,
          duration: 0.6,
          ease: "power2.out",
        });

        gsap.to(glare, {
          autoAlpha: 0,
          duration: 0.6,
        });
      };

      card.addEventListener("mouseenter", enter);
      card.addEventListener("mouseleave", leave);

      return () => {
        card.removeEventListener("mouseenter", enter);
        card.removeEventListener("mouseleave", leave);
        document.removeEventListener("mousemove", move);
      };
    });
  }, [categories]);
  const latestProducts = [...products].sort((a, b) => b.id - a.id).slice(0, 20);
  const filteredProducts =
    activeCategory === "All"
      ? latestProducts
      : latestProducts.filter((p) => p.category === activeCategory);

  useEffect(() => {

    const letters = document.querySelectorAll(".hero-letter");

    const animate = () => {
      gsap.to(letters, {
        y: -18,
        duration: 0.4,
        stagger: 0.05,
        ease: "power1.out",
        yoyo: true,
        repeat: 1
      });
    };

    letters.forEach((letter) => {

      letter.addEventListener("mouseenter", animate);

    });

  }, []);
  useEffect(() => {
    if (categoryRefs.current[0]) {
      const el = categoryRefs.current[0];

      gsap.set(underlineRef.current, {
        x: el.offsetLeft,
        width: el.offsetWidth,
      });
    }
  }, [categories]);
  useEffect(() => {
    categoryRefs.current.forEach((el) => {
      if (!el) return;

      el.addEventListener("mouseenter", () => {
        gsap.to(el, {
          scale: 1.1,
          duration: 0.2,
        });
      });

      el.addEventListener("mouseleave", () => {
        gsap.to(el, {
          scale: 1,
          duration: 0.2,
        });
      });
    });
  }, [categories]);
  useEffect(() => {
    gsap.fromTo(
      ".col-6",
      { opacity: 0, y: 20 },
      {
        opacity: 1,
        y: 0,
        duration: 0.5,
        stagger: 0.05,
        ease: "power2.out",
      }
    );
  }, [activeCategory]);