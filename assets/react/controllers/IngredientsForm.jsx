import React, { useEffect, useState } from "react";
import { useLoadIngredientData } from "../hooks/useFetch";
import { FaChevronDown, FaChevronUp } from "react-icons/fa";

const className = (...arr) => arr.filter(Boolean).join(" ");

export default function IngredientsForm() {
	const { items, load, loading } = useLoadIngredientData("/api/categories");
	const [categories, setCategories] = useState([]);

	useEffect(() => {
		load();
	}, [load]);

	useEffect(() => {
		if (items && items.length > 0) {
			setCategories(items);
		} else if (!loading) {
			console.error("Erreur lors du chargement des catégories");
		}
	}, [items, loading]);

	return (
		<div>
			<h3 className="form__label">Ingrédients</h3>
			<div className="section-ingredient">
				{categories.map((categorie) => (
					<IngredientsField
						key={categorie.id}
						categorie={categorie.name}
						totalIngredients={Object.values(categorie.ingredients)}
						visibleIngredients={
							Object.values(categorie.ingredients).length
						}
					/>
				))}
			</div>
		</div>
	);
}

function IngredientsField({ categorie, visibleIngredients, totalIngredients }) {
	const [isOpen, setIsOpen] = useState(false);

	const onClick = (e) => {
		e.preventDefault();
		setIsOpen(!isOpen);
	};

	return (
		<div className={className("card-categorie", isOpen && "isOpen")}>
			<div className="card-categorie__head">
				<div className="card-categorie__head-title">
					<h2>{categorie}</h2>
					<span>{visibleIngredients} ingrédients</span>
				</div>
				<button className="card-categorie__head-btn" onClick={onClick}>
					{isOpen ? <FaChevronUp /> : <FaChevronDown />}
				</button>
			</div>

			<div className="card-categorie__content">
				{totalIngredients.map((ingredient) => (
					<div
						className="card-categorie__content-ingredient"
						key={ingredient.id}
					>
						<div>{ingredient.name}</div>
					</div>
				))}
			</div>
		</div>
	);
}
