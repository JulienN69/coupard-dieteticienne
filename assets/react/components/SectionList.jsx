import React from "react";

export default function SectionList({ title, items, className }) {
	return (
		<div className={className}>
			<div className={`${className}-title`}>
				<h2>{title}</h2>
				<span></span>
			</div>
			<div className={`${className}-list`}>
				{items &&
					items.map((item, index) => (
						<div className={`${className}-item`} key={index}>
							<img src={`../${item.image}`} alt={item.name} />
							<p>{item.name}</p>
						</div>
					))}
			</div>
		</div>
	);
}
