import React from "react";
import PropTypes from "prop-types";
import { FaPlus } from "react-icons/fa";

export default function AddButton({
	label,
	onClick = () => {},
	icon = <FaPlus />,
}) {
	return (
		<div className="btn-container">
			<button className="addBtn" onClick={onClick}>
				{icon}
			</button>
			<span className="addBtn__span">{label}</span>
		</div>
	);
}

AddButton.propTypes = {
	label: PropTypes.string.isRequired,
	onClick: PropTypes.func,
	icon: PropTypes.node,
};
