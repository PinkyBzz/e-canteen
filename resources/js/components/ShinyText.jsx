import React from 'react';

export default function ShinyText({ text, speed = 3, className = '' }) {
  return (
    <span
      className={`shiny-text ${className}`}
      style={{ animationDuration: `${speed}s` }}
    >
      {text}
    </span>
  );
}
