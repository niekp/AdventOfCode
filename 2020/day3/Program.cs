using System;
using System.Collections.Generic;
using System.IO;

namespace Day3 {
	class Map {
		private string[] Lines { get; set; }
		private int X { get; set; }
		private int Y { get; set; }
		private int SpeedX { get; }
		private int SpeedY { get; }
		private List<(string Character, int Count)> Occurrences { get; set; }

		public Map(string Input, int SpeedX, int SpeedY) {
			Lines = Input.Split(Environment.NewLine);
			this.SpeedX = SpeedX;
			this.SpeedY = SpeedY;

			X = 0;
			Y = 0;
		}

		

	}

	class Program {
		static void Main(string[] args) {
			var map = new Map(File.ReadAllText("input.txt"), 3, 1);


		}
	}

	

}