import React from "react";
import { useEffect } from "react";
import { useLoadRecipe } from "../hooks/useLoadRecipe";

export default function Recipe({ id }) {
	const { items, load } = useLoadRecipe(`/api/recipes/${id}`);

	useEffect(() => {
		load();
	}, [load]);

	if (!items || Object.keys(items).length === 0) {
		return <div>Chargement...</div>;
	}
	console.log(items);

	return (
		<div className="recipeAlone">
			<div className="recipeAlone-card">
				<h3 className="recipeAlone-card__title">{items.title}</h3>
				{items.diets &&
					items.diets.map((diet, index) => (
						<p key={index}>{diet.name}</p> // Utilise une clé unique pour chaque élément
					))}
				<img
					className="recipeAlone-card__image"
					src={`../${items.image}`}
					alt={items.title}
				/>
				<p className="recipeAlone-card__description">
					{items.description}
				</p>
			</div>
		</div>
	);
}
