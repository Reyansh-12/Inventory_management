import gsap from "gsap";

export const introAnimation = (camera, product) => {
  const tl = gsap.timeline({ defaults: { ease: "power4.inOut" } });

  tl.from(camera.position, {
    z: 16,
    duration: 2,
  })
    .to(product.position, {
      y: 0,
      duration: 1.5,
    })
    .to(product.rotation, {
      y: Math.PI * 2,
      duration: 3,
    });

  return tl;
};