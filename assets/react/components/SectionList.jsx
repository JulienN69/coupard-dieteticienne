import React from "react";
import PropTypes from "prop-types";

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
						<div
							className={`${className}-item`}
							key={item.id || index}
						>
							<img src={`../${item.image}`} alt={item.name} />
							<p>{item.name}</p>
						</div>
					))}
			</div>
		</div>
	);
}

SectionList.propTypes = {
	title: PropTypes.string.isRequired,
	items: PropTypes.arrayOf(
		PropTypes.shape({
			image: PropTypes.string.isRequired,
			name: PropTypes.string.isRequired,
		})
	).isRequired,
	className: PropTypes.string.isRequired,
};
