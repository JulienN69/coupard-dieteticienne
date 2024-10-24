import React, { useEffect } from "react";
import { useLoadRecipe } from "../hooks/useLoadRecipe";
import SectionList from "../components/SectionList";

export default function RecipeDetail({ id }) {
	const { items, load } = useLoadRecipe(`/api/recipes/${id}`);

	const totalPreparationTime =
		(items.preparationTime || 0) +
		(items.cookingTime || 0) +
		(items.restingTime || 0);

	useEffect(() => {
		load();
	}, [load]);

	if (!items || Object.keys(items).length === 0) {
		return <div>Chargement...</div>;
	}

	return (
		<div className="recipe-detail-container">
			<div className="recipe-detail">
				<div className="recipe-detail__title-container">
					<h3>{items.title}</h3>
					{items.diets &&
						items.diets.map((diet, index) => (
							<p key={index}>{diet.name}</p>
						))}
				</div>
				<div className="recipe-detail__img-container">
					<img src={`../${items.image}`} alt={items.title} />
					<p>{items.description}</p>
				</div>
				<div className="recipe-detail__time-container">
					<p className="recipe-detail__total-time">
						Temps total : {totalPreparationTime} min
					</p>
					<div className="recipe-detail__times">
						<p>Préparation : {items.preparationTime || "-"} min</p>
						<p>Cuisson : {items.cookingTime || "-"} min</p>
						<p>Repos : {items.restingTime || "-"} min</p>
					</div>
				</div>
				<SectionList
					title="Ingrédients"
					items={items.ingredients}
					className="recipe-detail__ingredients"
				/>
				<SectionList
					title="Allergènes"
					items={items.allergens}
					className="recipe-detail__allergens"
				/>
				<div className="recipe-detail__steps">
					{items.steps &&
						items.steps.map((step) => (
							<div
								key={step.stepNumber}
								className="recipe-detail__steps-detail"
							>
								<h2>Étape {step.stepNumber}</h2>
								<p>{step.description}</p>
							</div>
						))}
				</div>
			</div>
		</div>
	);
}
