import React, { useRef } from 'react';
import { motion, useMotionValue, useSpring, useTransform } from 'motion/react';

export default function TiltedCard({ children, className = '', tilt = 12 }) {
  const ref = useRef(null);
  const x = useMotionValue(0);
  const y = useMotionValue(0);
  const xSpring = useSpring(x, { stiffness: 200, damping: 20 });
  const ySpring = useSpring(y, { stiffness: 200, damping: 20 });
  const rotateX = useTransform(ySpring, [-0.5, 0.5], [tilt, -tilt]);
  const rotateY = useTransform(xSpring, [-0.5, 0.5], [-tilt, tilt]);

  const handleMouseMove = (e) => {
    if (!ref.current) return;
    const rect = ref.current.getBoundingClientRect();
    x.set((e.clientX - rect.left) / rect.width - 0.5);
    y.set((e.clientY - rect.top) / rect.height - 0.5);
  };

  return (
    <motion.div
      ref={ref}
      onMouseMove={handleMouseMove}
      onMouseLeave={() => { x.set(0); y.set(0); }}
      style={{ rotateX, rotateY, transformStyle: 'preserve-3d' }}
      whileHover={{ scale: 1.04 }}
      className={`relative ${className}`}
    >
      <div style={{ transform: 'translateZ(40px)', transformStyle: 'preserve-3d' }}>
        {children}
      </div>
    </motion.div>
  );
}
