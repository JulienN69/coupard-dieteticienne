import React from "react";
import { useEffect } from "react";
import { useLoadData } from "../hooks/hook";

export default function Recipes() {
	const { items, load } = useLoadData("/api/recipes");

	useEffect(() => {
		load();
	}, [load]);

	return (
		<div className="recipes">
			{items.map((recipe, index) => (
				<div key={index} className="recipe-card">
					<img
						className="recipe-card__image"
						src={recipe.image}
						alt={recipe.title}
					/>
					<h3 className="recipe-card__title">{recipe.title}</h3>
					<p className="recipe-card__description">
						{recipe.description}
					</p>
				</div>
			))}
		</div>
	);
}
